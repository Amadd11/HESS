<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Tampilkan Dashboard Utama Analitik Admin HESS dengan filter periode.
     */
    public function index(Request $request): View
    {
        $periods = Period::orderByDesc('start_date')->get();

        $selectedPeriod = $request->filled('period_id')
            ? Period::find($request->integer('period_id'))
            : Period::active()->first();

        $selectedPeriod ??= $periods->first();
        $periodId = $selectedPeriod?->id;

        // Agregasi metrik utama responden & kepuasan dalam 1 query efisien
        $metrics = $periodId ? Response::where('period_id', $periodId)->selectRaw('
            COUNT(*) as total,
            COALESCE(ROUND(AVG(general_score), 1), 0) as avg_general,
            COALESCE(ROUND(AVG(intrinsic_score), 1), 0) as avg_intrinsic,
            COALESCE(ROUND(AVG(extrinsic_score), 1), 0) as avg_extrinsic,
            COALESCE(ROUND(AVG(hospital_score), 1), 0) as avg_hospital,
            SUM(CASE WHEN nps_category = "promoter" THEN 1 ELSE 0 END) as promoters,
            SUM(CASE WHEN nps_category = "passive" THEN 1 ELSE 0 END) as passives,
            SUM(CASE WHEN nps_category = "detractor" THEN 1 ELSE 0 END) as detractors
        ')->first() : null;

        $totalResponses = (int) ($metrics?->total ?? 0);
        $target = $selectedPeriod?->target ?? 0;
        $responseRate = $target > 0 ? round(($totalResponses / $target) * 100, 1) : 0;

        $avgGeneral = (float) ($metrics?->avg_general ?? 0);
        $avgIntrinsic = (float) ($metrics?->avg_intrinsic ?? 0);
        $avgExtrinsic = (float) ($metrics?->avg_extrinsic ?? 0);
        $avgHospital = (float) ($metrics?->avg_hospital ?? 0);

        $promoters = (int) ($metrics?->promoters ?? 0);
        $passives = (int) ($metrics?->passives ?? 0);
        $detractors = (int) ($metrics?->detractors ?? 0);
        $npsScore = $totalResponses > 0 ? round((($promoters - $detractors) / $totalResponses) * 100) : 0;

        // Rata-rata Skor per Unit Kerja
        $unitScores = $periodId
            ? Response::where('period_id', $periodId)
                ->select('unit', DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('unit')
                ->orderByDesc('avg_score')
                ->get()
            : collect();

        // Rata-rata Skor per Kelompok Profesi
        $professionScores = $periodId
            ? Response::where('period_id', $periodId)
                ->select('profession', DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('profession')
                ->orderByDesc('avg_score')
                ->get()
            : collect();

        // 10 Masukan Kualitatif Terbaru
        $recentFeedbacks = $periodId
            ? Response::where('period_id', $periodId)
                ->where(fn ($q) => $q->whereNotNull('like_text')->orWhereNotNull('improve_text'))
                ->latest('completed_at')
                ->take(10)
                ->get()
            : collect();

        // Rata-rata Skor per Kategori & Dimensi Rumah Sakit
        $categoryScores = $periodId
            ? DB::table('categories')
                ->leftJoin('questions', fn ($j) => $j->on('questions.category_id', '=', 'categories.id')->whereNull('questions.deleted_at')->where('questions.is_active', true))
                ->leftJoin('answers', fn ($j) => $j->on('answers.question_id', '=', 'questions.id')->whereIn('answers.response_id', fn ($q) => $q->select('id')->from('responses')->where('period_id', $periodId)))
                ->whereNull('categories.deleted_at')
                ->select(
                    'categories.id',
                    'categories.name',
                    'categories.code',
                    'categories.type',
                    'categories.order',
                    DB::raw('COUNT(DISTINCT questions.id) as questions_count'),
                    DB::raw('COALESCE(ROUND((AVG(answers.score) / 5) * 100, 1), 0) as percentage_score'),
                    DB::raw('COALESCE(ROUND(AVG(answers.score), 2), 0) as avg_raw_score')
                )
                ->groupBy('categories.id', 'categories.name', 'categories.code', 'categories.type', 'categories.order')
                ->orderBy('categories.order')
                ->get()
            : collect();

        $hospitalCategoryScores = $categoryScores->where('type', 'hospital');

        return view('admin.dashboard', [
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'activePeriod' => $selectedPeriod,
            'totalResponses' => $totalResponses,
            'target' => $target,
            'responseRate' => $responseRate,
            'avgGeneral' => $avgGeneral,
            'avgIntrinsic' => $avgIntrinsic,
            'avgExtrinsic' => $avgExtrinsic,
            'avgHospital' => $avgHospital,
            'promoters' => $promoters,
            'passives' => $passives,
            'detractors' => $detractors,
            'npsScore' => $npsScore,
            'unitScores' => $unitScores,
            'professionScores' => $professionScores,
            'recentFeedbacks' => $recentFeedbacks,
            'categoryScores' => $categoryScores,
            'hospitalCategoryScores' => $hospitalCategoryScores,
        ]);
    }

    /**
     * Ekspor data hasil respon survei ke format CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $periodId = $request->integer('period_id') ?: Period::active()->value('id');
        $period = Period::find($periodId);

        $filename = 'hess_survey_responses_' . ($period?->slug ?? 'all') . '.csv';

        return response()->streamDownload(function () use ($periodId) {
            $handle = fopen('php://output', 'w');

            // Header kolom CSV
            fputcsv($handle, [
                'ID Respon',
                'Waktu Pengisian',
                'Profesi',
                'Unit Kerja',
                'Status Kepegawaian',
                'Lama Bekerja',
                'Kepuasan Umum MSQ (%)',
                'Kepuasan Intrinsik (%)',
                'Kepuasan Ekstrinsik (%)',
                'Faktor Rumah Sakit (%)',
                'Skor Keseluruhan (1-5)',
                'eNPS (0-10)',
                'Kategori eNPS',
                'Hal yang Disukai',
                'Hal yang Perlu Diperbaiki',
            ]);

            $query = Response::query();
            if ($periodId) {
                $query->where('period_id', $periodId);
            }

            $query->chunk(200, function ($responses) use ($handle) {
                foreach ($responses as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->completed_at?->format('Y-m-d H:i:s') ?? $r->created_at->format('Y-m-d H:i:s'),
                        $r->profession,
                        $r->unit,
                        $r->status,
                        $r->tenure,
                        $r->general_score,
                        $r->intrinsic_score,
                        $r->extrinsic_score,
                        $r->hospital_score,
                        $r->overall_score,
                        $r->nps_score,
                        $r->nps_category,
                        $r->like_text ?? '',
                        $r->improve_text ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

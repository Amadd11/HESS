<?php

namespace App\Services;

use App\Models\Demographic;
use App\Models\Period;
use App\Models\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardService
{
    /**
     * Ambil seluruh dataset analitik yang diperlukan oleh dashboard admin.
     *
     * @return array<string, mixed>
     */
    public function getDashboardData(Request $request): array
    {
        $periods = Period::orderByDesc('start_date')->get();

        $selectedPeriod = $request->filled('period_id')
            ? Period::find($request->integer('period_id'))
            : Period::active()->first();

        $selectedPeriod ??= $periods->first();
        $periodId = $selectedPeriod?->id;

        $baseQuery = $this->buildFilteredQuery($request, $periodId);

        // 1. Agregasi KPI Utama
        $metrics = $periodId ? (clone $baseQuery)->selectRaw('
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
        $npsScore = $totalResponses > 0 ? (int) round((($promoters - $detractors) / $totalResponses) * 100) : 0;

        // 2. Skor per Unit Kerja
        $unitScores = $periodId
            ? (clone $baseQuery)
                ->select('unit', DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('unit')
                ->orderByDesc('avg_score')
                ->get()
            : collect();

        // 3. Skor per Kelompok Profesi
        $professionScores = $periodId
            ? (clone $baseQuery)
                ->select('profession', DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('profession')
                ->orderByDesc('avg_score')
                ->get()
            : collect();

        // 4. Masukan Kualitatif Terbaru & Total Aspirasi
        $recentFeedbacks = $periodId
            ? (clone $baseQuery)
                ->where(fn ($q) => $q->whereNotNull('like_text')->orWhereNotNull('improve_text'))
                ->latest('completed_at')
                ->take(12)
                ->get()
            : collect();

        $totalFeedbacksCount = $periodId
            ? (clone $baseQuery)
                ->where(fn ($q) => $q->whereNotNull('like_text')->orWhereNotNull('improve_text'))
                ->count()
            : 0;

        // 5. Agregasi Kategori & 8 Dimensi Rumah Sakit
        $categoryScores = $this->getCategoryScores($baseQuery, $periodId);
        $hospitalCategoryScores = $categoryScores->where('type', 'hospital');

        // 6. Actionable Insights: Top 5 Strengths vs Top 5 Priority Areas
        [$topStrengths, $topImprovements] = $this->getActionableInsights($baseQuery, $periodId, $totalResponses);

        // 7. Opsi demografi & status filter aktif
        $demographics = Demographic::getGroupedOptions();
        $hasFilters = $request->anyFilled(['profession', 'unit', 'status', 'tenure']);

        return [
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'activePeriod' => $selectedPeriod,
            'demographics' => $demographics,
            'hasFilters' => $hasFilters,
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
            'totalFeedbacksCount' => $totalFeedbacksCount,
            'categoryScores' => $categoryScores,
            'hospitalCategoryScores' => $hospitalCategoryScores,
            'topStrengths' => $topStrengths,
            'topImprovements' => $topImprovements,
        ];
    }

    /**
     * Bangun query Response dengan filter multi-dimensi demografi.
     */
    public function buildFilteredQuery(Request $request, ?int $periodId = null): Builder
    {
        $query = Response::query();

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        if ($request->filled('profession')) {
            $query->where('profession', $request->string('profession'));
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->string('unit'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('tenure')) {
            $query->where('tenure', $request->string('tenure'));
        }

        return $query;
    }

    /**
     * Agregasi skor per kategori kuesioner dan dimensi rumah sakit.
     *
     * @return Collection<int, object>
     */
    public function getCategoryScores(Builder $baseQuery, ?int $periodId): Collection
    {
        if (! $periodId) {
            return collect();
        }

        return DB::table('categories')
            ->leftJoin('questions', fn ($j) => $j->on('questions.category_id', '=', 'categories.id')->whereNull('questions.deleted_at')->where('questions.is_active', true))
            ->leftJoin('answers', fn ($j) => $j->on('answers.question_id', '=', 'questions.id')->whereIn('answers.response_id', (clone $baseQuery)->select('id')))
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
            ->get();
    }

    /**
     * Hitung Top 5 Strengths (Kekuatan) dan Top 5 Priority Areas (Area Perbaikan RTL).
     *
     * @return array{0: Collection<int, object>, 1: Collection<int, object>}
     */
    public function getActionableInsights(Builder $baseQuery, ?int $periodId, int $totalResponses): array
    {
        if (! $periodId || $totalResponses === 0) {
            return [collect(), collect()];
        }

        $questionScores = DB::table('questions')
            ->join('categories', 'categories.id', '=', 'questions.category_id')
            ->join('answers', 'answers.question_id', '=', 'questions.id')
            ->whereIn('answers.response_id', (clone $baseQuery)->select('id'))
            ->whereNull('questions.deleted_at')
            ->where('questions.is_active', true)
            ->select(
                'questions.id',
                'questions.code',
                'questions.text',
                'categories.name as category_name',
                'categories.code as category_code',
                'categories.type as category_type',
                DB::raw('ROUND(AVG(answers.score), 2) as avg_score'),
                DB::raw('ROUND((AVG(answers.score) / 5) * 100, 1) as percentage_score'),
                DB::raw('COUNT(answers.id) as answers_count')
            )
            ->groupBy('questions.id', 'questions.code', 'questions.text', 'categories.name', 'categories.code', 'categories.type')
            ->get();

        $topStrengths = $questionScores->sortByDesc('avg_score')->take(5)->values();
        $topImprovements = $questionScores->sortBy('avg_score')->take(5)->values();

        return [$topStrengths, $topImprovements];
    }

    /**
     * Ekspor data respon survei terfilter ke file CSV streaming.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $periodId = $request->integer('period_id') ?: Period::active()->value('id');
        $period = Period::find($periodId);

        $filename = 'hess_survey_responses_' . ($period?->slug ?? 'all') . '.csv';
        $query = $this->buildFilteredQuery($request, $periodId);

        return response()->streamDownload(function () use ($query) {
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

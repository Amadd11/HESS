<?php

namespace App\Services;

use App\Models\Demographic;
use App\Models\Period;
use App\Models\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Ambil seluruh dataset analitik yang diperlukan oleh dashboard admin.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function getDashboardData(array $filters = []): array
    {
        $periods = Period::orderByDesc('start_date')->get();

        $selectedPeriodId = ! empty($filters['period_id']) ? (int) $filters['period_id'] : null;
        $selectedPeriod = $selectedPeriodId
            ? Period::find($selectedPeriodId)
            : Period::active()->first();

        $selectedPeriod ??= $periods->first();
        $periodId = $selectedPeriod?->id;

        $baseQuery = $this->buildFilteredQuery($filters, $periodId);

        // 1. Agregasi KPI Utama
        $metrics = $periodId ? (clone $baseQuery)->selectRaw('
            COUNT(*) as total,
            COALESCE(ROUND(AVG(general_score), 1), 0) as avg_general,
            SUM(CASE WHEN nps_category = "promoter" THEN 1 ELSE 0 END) as promoters,
            SUM(CASE WHEN nps_category = "passive" THEN 1 ELSE 0 END) as passives,
            SUM(CASE WHEN nps_category = "detractor" THEN 1 ELSE 0 END) as detractors
        ')->first() : null;

        $totalResponses = (int) ($metrics?->total ?? 0);
        $target = $selectedPeriod?->target ?? 0;
        $responseRate = $target > 0 ? round(($totalResponses / $target) * 100, 1) : 0;

        $avgGeneral = (float) ($metrics?->avg_general ?? 0);
        $avgHospital = $avgGeneral;

        $promoters = (int) ($metrics?->promoters ?? 0);
        $passives = (int) ($metrics?->passives ?? 0);
        $detractors = (int) ($metrics?->detractors ?? 0);
        $npsScore = $totalResponses > 0 ? (int) round((($promoters - $detractors) / $totalResponses) * 100) : 0;

        // 2. Skor per Unit Kerja
        $unitToDir = [];
        foreach (Demographic::DIRECTORATE_UNITS as $dir => $units) {
            foreach ($units as $u) {
                $unitToDir[$u] = $dir;
            }
        }

        $unitScores = $periodId
            ? (clone $baseQuery)
                ->select('unit', DB::raw('MAX(directorate) as directorate'), DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('unit')
                ->orderByDesc('avg_score')
                ->get()
                ->map(function ($item) use ($unitToDir) {
                    if (empty($item->directorate) && isset($unitToDir[$item->unit])) {
                        $item->directorate = $unitToDir[$item->unit];
                    }

                    return $item;
                })
            : collect();

        // 3. Skor per Kelompok Profesi
        $professionScores = $periodId
            ? (clone $baseQuery)
                ->select('profession', DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('profession')
                ->orderByDesc('avg_score')
                ->get()
            : collect();

        // 4. Skor per Direktorat
        $directorateScores = $periodId
            ? (clone $baseQuery)
                ->whereNotNull('directorate')
                ->where('directorate', '!=', '')
                ->select('directorate', DB::raw('COUNT(*) as total'), DB::raw('ROUND(AVG(general_score), 1) as avg_score'))
                ->groupBy('directorate')
                ->orderByDesc('avg_score')
                ->get()
            : collect();

        // 5. Agregasi Kategori & 8 Dimensi Rumah Sakit
        $categoryScores = $this->getCategoryScores($baseQuery, $periodId);
        $hospitalCategoryScores = $categoryScores;

        // 6. Actionable Insights: Top 5 Strengths vs Top 5 Priority Areas
        [$topStrengths, $topImprovements] = $this->getActionableInsights($baseQuery, $periodId, $totalResponses);

        // 7. Opsi demografi & status filter aktif
        $demographics = Demographic::getGroupedOptions();
        $hasFilters = ! empty($filters['profession']) || ! empty($filters['directorate']) || ! empty($filters['unit']) || ! empty($filters['status']) || ! empty($filters['tenure']) || ! empty($filters['age']) || ! empty($filters['gender']) || ! empty($filters['education']) || ! empty($filters['income']);

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
            'avgHospital' => $avgHospital,
            'promoters' => $promoters,
            'passives' => $passives,
            'detractors' => $detractors,
            'npsScore' => $npsScore,
            'unitScores' => $unitScores,
            'professionScores' => $professionScores,
            'directorateScores' => $directorateScores,
            'categoryScores' => $categoryScores,
            'hospitalCategoryScores' => $hospitalCategoryScores,
            'topStrengths' => $topStrengths,
            'topImprovements' => $topImprovements,
        ];
    }

    /**
     * Bangun query Response dengan filter multi-dimensi demografi, periode, NPS, dan pencarian.
     *
     * @param  array<string, mixed>  $filters
     */
    public function buildFilteredQuery(array $filters = [], ?int $periodId = null): Builder
    {
        $query = Response::query()->with('period');

        if ($periodId) {
            $query->where('period_id', $periodId);
        } elseif (! empty($filters['period_id']) && $filters['period_id'] !== 'all') {
            $query->where('period_id', (int) $filters['period_id']);
        }

        foreach (['profession', 'directorate', 'unit', 'status', 'tenure', 'age', 'gender', 'education', 'income'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, trim((string) $filters[$field]));
            }
        }

        if (! empty($filters['nps_category'])) {
            $query->where('nps_category', trim((string) $filters['nps_category']));
        }

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('like_text', 'like', "%{$search}%")
                    ->orWhere('improve_text', 'like', "%{$search}%")
                    ->orWhere('directorate', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Agregasi skor per kategori kuesioner dan dimensi rumah sakit (skala 4 poin).
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
                'categories.order',
                DB::raw('COUNT(DISTINCT questions.id) as questions_count'),
                DB::raw('COALESCE(ROUND((AVG(answers.score) / 4) * 100, 1), 0) as percentage_score'),
                DB::raw('COALESCE(ROUND(AVG(answers.score), 2), 0) as avg_raw_score')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.code', 'categories.order')
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
                DB::raw('ROUND(AVG(answers.score), 2) as avg_score'),
                DB::raw('ROUND((AVG(answers.score) / 4) * 100, 1) as percentage_score'),
                DB::raw('COUNT(answers.id) as answers_count')
            )
            ->groupBy('questions.id', 'questions.code', 'questions.text', 'categories.name', 'categories.code')
            ->get();

        $topStrengths = $questionScores->sortByDesc('avg_score')->take(5)->values();
        $topImprovements = $questionScores->sortBy('avg_score')->take(5)->values();

        return [$topStrengths, $topImprovements];
    }
}

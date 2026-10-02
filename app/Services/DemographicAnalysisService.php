<?php

namespace App\Services;

use App\Models\Demographic;
use App\Models\Period;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DemographicAnalysisService
{
    public function __construct(
        public DashboardService $dashboardService
    ) {}

    /**
     * Ambil dataset khusus yang diperlukan oleh halaman Analisis Demografi Responden.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function getDemographicPageData(array $filters = []): array
    {
        $periods = Period::orderByDesc('start_date')->get();

        $selectedPeriodId = ! empty($filters['period_id']) ? (int) $filters['period_id'] : null;
        $selectedPeriod = $selectedPeriodId ? Period::find($selectedPeriodId) : Period::active()->first();
        $selectedPeriod ??= $periods->first();
        $periodId = $selectedPeriod?->id;

        $baseQuery = $this->dashboardService->buildFilteredQuery($filters, $periodId);
        $totalResponses = $periodId ? (int) (clone $baseQuery)->count() : 0;

        $demographics = Demographic::getGroupedOptions();
        $filterKeys = ['directorate', 'unit', 'profession', 'status', 'tenure', 'age', 'gender', 'education', 'income'];
        $hasFilters = request()->anyFilled($filterKeys) || collect($filterKeys)->contains(fn ($k) => ! empty($filters[$k]));
        $filterParams = array_intersect_key($filters, array_flip($filterKeys));

        $demographicData = $this->getDemographicDistributions($baseQuery, $totalResponses, $demographics, $filterParams, $periodId);

        $allResponsesUrl = route('admin.responses.index', array_filter(array_merge(
            $filterParams,
            ['period_id' => $periodId]
        ), fn ($v) => ! is_null($v) && $v !== ''));

        $defaultDimension = $this->getDefaultDimension();

        $pageData = [
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'activePeriod' => $selectedPeriod,
            'demographics' => $demographics,
            'hasFilters' => $hasFilters,
            'totalResponses' => $totalResponses,
            'demographicData' => $demographicData,
            'allResponsesUrl' => $allResponsesUrl,
        ];

        foreach (['age', 'gender', 'income', 'status', 'education'] as $dim) {
            $pageData[$dim . 'Data'] = $demographicData[$dim] ?? $defaultDimension;
            $pageData['dominant' . ucfirst($dim)] = $demographicData[$dim]['dominant'] ?? null;
        }

        return $pageData;
    }

    /**
     * Agregasi sebaran data demografi responden (Usia, Jenis Kelamin, Pendapatan, Status, Pendidikan)
     *
     * @param  array<string, array<int, string>>  $demographicsOptions
     * @param  array<string, mixed>  $currentFilters
     * @return array<string, mixed>
     */
    public function getDemographicDistributions(Builder $baseQuery, int $totalResponses, array $demographicsOptions = [], array $currentFilters = [], ?int $periodId = null): array
    {
        $dimensions = $this->getDimensionDefinitions();
        $result = [];

        foreach ($dimensions as $field => $meta) {
            $masterList = $demographicsOptions[$meta['masterKey']] ?? [];
            $colorPalette = $meta['colors'];
            $colorCount = count($colorPalette);

            $dbCounts = (clone $baseQuery)
                ->whereNotNull($field)
                ->where($field, '!=', '')
                ->select($field, DB::raw('COUNT(*) as total'))
                ->groupBy($field)
                ->pluck('total', $field)
                ->toArray();

            $allLabels = array_values(array_unique(array_merge($masterList, array_keys($dbCounts))));
            $items = [];
            $labels = [];
            $counts = [];
            $percentages = [];

            foreach ($allLabels as $idx => $label) {
                $count = (int) ($dbCounts[$label] ?? 0);
                $percentage = $totalResponses > 0 ? round(($count / $totalResponses) * 100, 1) : 0.0;
                $color = $colorPalette[$idx % $colorCount];

                $drillParams = array_filter(array_merge(
                    $currentFilters,
                    ['period_id' => $periodId, $field => (string) $label]
                ), fn ($val) => ! is_null($val) && $val !== '');

                $styles = $this->formatItemStyles($field, (string) $label);

                $items[] = [
                    'label' => (string) $label,
                    'count' => $count,
                    'percentage' => $percentage,
                    'color' => $color,
                    'drill_url' => $count > 0 ? route('admin.responses.index', $drillParams) : null,
                    'row_bg' => $styles['row_bg'],
                    'badge_style' => $styles['badge_style'],
                ];
                $labels[] = (string) $label;
                $counts[] = $count;
                $percentages[] = $percentage;
            }

            $sumCount = array_sum($counts);

            $result[$field] = [
                'title' => $meta['title'],
                'subtitle' => $meta['subtitle'],
                'theme' => $meta['theme'],
                'theme_classes' => $this->getThemeClasses($meta['theme']),
                'icon' => $meta['icon'],
                'chart_id' => $meta['chart_id'],
                'items' => $items,
                'labels' => $labels,
                'counts' => $counts,
                'percentages' => $percentages,
                'total' => $sumCount,
                'has_data' => count($items) > 0 && $sumCount > 0,
                'dominant' => $this->calculateDominant($items),
                'gender_ratio' => $field === 'gender' ? $this->calculateGenderRatio($items) : null,
                'colors' => $colorPalette,
            ];
        }

        return $result;
    }

    /**
     * Definisi konfigurasi tiap dimensi demografi.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getDimensionDefinitions(): array
    {
        return [
            'age' => [
                'title' => 'Distribusi Rentang Usia',
                'subtitle' => 'Komposisi generasi dan kelompok umur responden',
                'masterKey' => 'ages',
                'theme' => 'indigo',
                'icon' => 'clock',
                'chart_id' => 'demographicAgeChart',
                'colors' => ['#6366f1', '#3b82f6', '#0ea5e9', '#06b6d4', '#14b8a6', '#64748b'],
            ],
            'gender' => [
                'title' => 'Distribusi Jenis Kelamin',
                'subtitle' => 'Proporsi responden laki-laki dan perempuan',
                'masterKey' => 'genders',
                'theme' => 'sky',
                'icon' => 'users',
                'chart_id' => 'demographicGenderChart',
                'colors' => ['#0284c7', '#f43f5e'],
            ],
            'income' => [
                'title' => 'Distribusi Tingkat Pendapatan',
                'subtitle' => 'Sebaran take-home pay / penghasilan responden',
                'masterKey' => 'incomes',
                'theme' => 'emerald',
                'icon' => 'currency',
                'chart_id' => 'demographicIncomeChart',
                'colors' => ['#10b981', '#059669', '#0d9488', '#0891b2', '#0284c7', '#64748b'],
            ],
            'status' => [
                'title' => 'Distribusi Status Kepegawaian',
                'subtitle' => 'Proporsi pegawai tetap, kontrak, dan lainnya',
                'masterKey' => 'statuses',
                'theme' => 'purple',
                'icon' => 'briefcase',
                'chart_id' => 'demographicStatusChart',
                'colors' => ['#8b5cf6', '#a855f7', '#d946ef', '#64748b'],
            ],
            'education' => [
                'title' => 'Distribusi Jenjang Pendidikan',
                'subtitle' => 'Tingkat kualifikasi akademik responden pegawai',
                'masterKey' => 'educations',
                'theme' => 'blue',
                'icon' => 'academic',
                'chart_id' => 'demographicEducationChart',
                'colors' => ['#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#ec4899', '#f43f5e', '#64748b'],
            ],
        ];
    }

    protected function getThemeClasses(string $theme): array
    {
        return [
            'bg' => "bg-{$theme}-50",
            'text' => "text-{$theme}-700",
            'border' => "border-{$theme}-100",
        ];
    }

    protected function calculateDominant(array $items): ?array
    {
        return collect($items)->where('count', '>', 0)->sortByDesc('count')->first();
    }

    protected function calculateGenderRatio(array $items): ?string
    {
        $male = collect($items)->first(fn ($i) => stripos($i['label'], 'laki') !== false)['count'] ?? 0;
        $female = collect($items)->first(fn ($i) => stripos($i['label'], 'perempuan') !== false)['count'] ?? 0;

        return ($male > 0 && $female > 0) ? '1 Laki-laki : ' . round($female / $male, 1) . ' Perempuan' : null;
    }

    protected function formatItemStyles(string $field, string $label): array
    {
        if ($field === 'gender') {
            $isMale = stripos($label, 'laki') !== false;

            return [
                'row_bg' => $isMale ? 'bg-sky-50/70 border-sky-100' : 'bg-rose-50/70 border-rose-100',
                'badge_style' => $isMale ? 'text-sky-700 bg-sky-100 border-sky-200' : 'text-rose-700 bg-rose-100 border-rose-200',
            ];
        }

        return [
            'row_bg' => 'bg-gray-50/70 border-gray-100',
            'badge_style' => 'text-gray-700 bg-gray-100/80 border-gray-200/80',
        ];
    }

    public function getDefaultDimension(): array
    {
        return [
            'title' => '',
            'subtitle' => '',
            'theme' => 'indigo',
            'theme_classes' => $this->getThemeClasses('indigo'),
            'icon' => 'clock',
            'chart_id' => '',
            'items' => [],
            'labels' => [],
            'counts' => [],
            'percentages' => [],
            'total' => 0,
            'has_data' => false,
            'dominant' => null,
            'gender_ratio' => null,
            'colors' => [],
        ];
    }
}

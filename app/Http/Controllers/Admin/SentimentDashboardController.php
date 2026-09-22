<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SentimentFilterRequest;
use App\Models\Demographic;
use App\Models\Period;
use App\Models\Response;
use App\Services\DashboardAnalyticsService;
use App\Services\KeywordExtractionService;
use App\Services\SentimentAnalysisService;
use App\Services\SentimentExportService;
use App\Services\SentimentInsightService;
use App\Services\WordCloudService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SentimentDashboardController extends Controller
{
    public function __construct(
        protected SentimentAnalysisService $analysisService,
        protected DashboardAnalyticsService $analyticsService,
        protected WordCloudService $wordCloudService,
        protected KeywordExtractionService $keywordService,
        protected SentimentInsightService $insightService,
        protected SentimentExportService $exportService
    ) {}

    /**
     * Tampilkan Dashboard Analisis Sentimen (Clean Architecture).
     */
    public function index(SentimentFilterRequest $request): View
    {
        $filters = $request->filters();

        // 1. Data Periode Survei
        $periods = Period::orderByDesc('start_date')->get();
        $selectedPeriodId = ! empty($filters['period_id']) ? (int) $filters['period_id'] : null;
        $selectedPeriod = $selectedPeriodId
            ? Period::find($selectedPeriodId)
            : Period::active()->first();
        $selectedPeriod ??= $periods->first();
        $periodId = $selectedPeriod?->id;

        // 2. Query Data Respon dengan Filter
        $query = Response::query()->with('period');

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        foreach (['unit', 'profession', 'status', 'tenure'] as $col) {
            if (! empty($filters[$col])) {
                $query->where($col, trim((string) $filters[$col]));
            }
        }

        // Respon yang memiliki teks feedback terbuka
        $allResponses = $query->where(function ($q) {
            $q->whereNotNull('like_text')->orWhereNotNull('improve_text');
        })->get();

        // 3. Analisis Agregat Koleksi
        $analysisResult = $this->analysisService->analyzeCollection($allResponses);
        $classifiedResponses = $analysisResult['classified_responses'];
        $totalWords = $analysisResult['total_words'];

        // 4. Hitung Metrik Analytics (KPI, Trend, Breakdown)
        $kpis = $this->analyticsService->getKpiSummary($classifiedResponses, $totalWords);
        $trend = $this->analyticsService->getSentimentTrend();
        $breakdown = $this->analyticsService->getBreakdown($classifiedResponses);

        // 5. Ekstraksi Kata Kunci & Word Cloud
        $topPositive = $this->keywordService->getTopKeywords($analysisResult['positive_frequencies'], 10);
        $topNegative = $this->keywordService->getTopKeywords($analysisResult['negative_frequencies'], 10);

        $wordClouds = [
            'positive' => $this->wordCloudService->buildCloud($analysisResult['positive_frequencies'], 'positive', 20),
            'neutral' => $this->wordCloudService->buildCloud($analysisResult['neutral_frequencies'], 'neutral', 20),
            'negative' => $this->wordCloudService->buildCloud($analysisResult['negative_frequencies'], 'negative', 20),
        ];

        // 6. Ringkasan AI & Insight Eksekutif
        $insights = $this->insightService->generateInsights($kpis, $topPositive, $topNegative);

        // 7. Feedback Explorer Table (dengan filter sentimen, search, dan pagination)
        $tableItems = $classifiedResponses;

        if (! empty($filters['sentiment']) && $filters['sentiment'] !== 'all') {
            $tableItems = $tableItems->where('sentiment', $filters['sentiment']);
        }

        if (! empty($filters['search'])) {
            $s = mb_strtolower(trim((string) $filters['search']), 'UTF-8');
            $tableItems = $tableItems->filter(function ($item) use ($s) {
                return str_contains(mb_strtolower((string) ($item['like_text'] ?? ''), 'UTF-8'), $s)
                    || str_contains(mb_strtolower((string) ($item['improve_text'] ?? ''), 'UTF-8'), $s)
                    || str_contains(mb_strtolower((string) ($item['unit'] ?? ''), 'UTF-8'), $s)
                    || str_contains(mb_strtolower((string) ($item['profession'] ?? ''), 'UTF-8'), $s);
            });
        }

        $perPage = 8;
        $currentPage = (int) ($request->query('page', 1));
        $pagedSlice = $tableItems->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedFeedback = new LengthAwarePaginator(
            $pagedSlice,
            $tableItems->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 8. Opsi Filter Demografi
        $demographics = [
            'units' => Demographic::active()->where('type', 'unit')->pluck('name')->all(),
            'professions' => Demographic::active()->where('type', 'profession')->pluck('name')->all(),
            'statuses' => Demographic::active()->where('type', 'status')->pluck('name')->all(),
            'tenures' => Demographic::active()->where('type', 'tenure')->pluck('name')->all(),
        ];

        if (empty($demographics['units'])) {
            $demographics['units'] = Response::whereNotNull('unit')->distinct()->pluck('unit')->all();
        }
        if (empty($demographics['professions'])) {
            $demographics['professions'] = Response::whereNotNull('profession')->distinct()->pluck('profession')->all();
        }
        if (empty($demographics['statuses'])) {
            $demographics['statuses'] = Response::whereNotNull('status')->distinct()->pluck('status')->all();
        }
        if (empty($demographics['tenures'])) {
            $demographics['tenures'] = Response::whereNotNull('tenure')->distinct()->pluck('tenure')->all();
        }

        return view('admin.sentiment.index', [
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'demographics' => $demographics,
            'hasActiveFilters' => $request->hasActiveFilters(),
            'kpis' => $kpis,
            'trend' => $trend,
            'breakdown' => $breakdown,
            'wordClouds' => $wordClouds,
            'topPositive' => $topPositive,
            'topNegative' => $topNegative,
            'insights' => $insights,
            'feedbackPaginator' => $paginatedFeedback,
            'totalFeedbackCount' => $allResponses->count(),
        ]);
    }

    /**
     * Ekspor data feedback sentimen ke format Excel (.xlsx).
     */
    public function export(SentimentFilterRequest $request): StreamedResponse
    {
        return $this->exportService->exportExcel($request->filters());
    }
}

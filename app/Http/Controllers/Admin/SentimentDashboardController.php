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

        foreach (['directorate', 'unit', 'profession', 'status', 'tenure'] as $col) {
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
                    || str_contains(mb_strtolower((string) ($item['directorate'] ?? ''), 'UTF-8'), $s)
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

        // 8. Proporsi Sentimen (Word Stats & Donut Data)
        $posWords = (int) array_sum($analysisResult['positive_frequencies']);
        $neuWords = (int) array_sum($analysisResult['neutral_frequencies']);
        $negWords = (int) array_sum($analysisResult['negative_frequencies']);
        $totalSentimentWords = $posWords + $neuWords + $negWords;

        $useWords = $totalSentimentWords > 0;
        $propTotal = $useWords ? $totalSentimentWords : $classifiedResponses->count();
        $propPos = $useWords ? $posWords : ($kpis['positive']['count'] ?? 0);
        $propNeu = $useWords ? $neuWords : ($kpis['neutral']['count'] ?? 0);
        $propNeg = $useWords ? $negWords : ($kpis['negative']['count'] ?? 0);
        $propUnit = $useWords ? 'kata' : 'respon';

        $propPosPct = $propTotal > 0 ? (int) round(($propPos / $propTotal) * 100) : 0;
        $propNeuPct = $propTotal > 0 ? (int) round(($propNeu / $propTotal) * 100) : 0;
        $propNegPct = $propTotal > 0 ? max(0, 100 - $propPosPct - $propNeuPct) : 0;

        $sentimentProportion = [
            'total' => $propTotal,
            'unit' => $propUnit,
            'positive' => [
                'count' => $propPos,
                'percent' => $propPosPct,
            ],
            'neutral' => [
                'count' => $propNeu,
                'percent' => $propNeuPct,
            ],
            'negative' => [
                'count' => $propNeg,
                'percent' => $propNegPct,
            ],
            'series' => [$propPos, $propNeu, $propNeg],
        ];

        // 9. Contoh Kutipan Responden Per Kategori Sentimen
        $sampleQuotes = [
            'positive' => $classifiedResponses->first(fn ($r) => $r['sentiment'] === 'positive' && (! empty($r['like_text']) || ! empty($r['improve_text']))),
            'neutral' => $classifiedResponses->first(fn ($r) => $r['sentiment'] === 'neutral' && (! empty($r['like_text']) || ! empty($r['improve_text']))),
            'negative' => $classifiedResponses->first(fn ($r) => $r['sentiment'] === 'negative' && (! empty($r['like_text']) || ! empty($r['improve_text']))),
        ];

        // 10. Opsi Filter Demografi
        $demographics = [
            'directorates' => Demographic::active()->where('type', 'directorate')->pluck('name')->all(),
            'units' => Demographic::active()->where('type', 'unit')->pluck('name')->all(),
            'professions' => Demographic::active()->where('type', 'profession')->pluck('name')->all(),
            'statuses' => Demographic::active()->where('type', 'status')->pluck('name')->all(),
            'tenures' => Demographic::active()->where('type', 'tenure')->pluck('name')->all(),
        ];

        if (empty($demographics['directorates'])) {
            $demographics['directorates'] = Response::whereNotNull('directorate')->distinct()->pluck('directorate')->all();
        }
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
            'sentimentProportion' => $sentimentProportion,
            'sampleQuotes' => $sampleQuotes,
        ]);
    }

    /**
     * Ekspor data feedback sentimen ke format Excel (.xlsx).
     */
    public function export(SentimentFilterRequest $request): StreamedResponse
    {
        return $this->exportService->exportExcel($request->filters());
    }

    /**
     * Tampilkan Laporan Eksekutif Analisis Sentimen (Print / PDF View).
     */
    public function report(SentimentFilterRequest $request): View
    {
        $filters = $request->filters();

        $periods = Period::orderByDesc('start_date')->get();
        $selectedPeriodId = ! empty($filters['period_id']) ? (int) $filters['period_id'] : null;
        $selectedPeriod = $selectedPeriodId
            ? Period::find($selectedPeriodId)
            : Period::active()->first();
        $selectedPeriod ??= $periods->first();
        $periodId = $selectedPeriod?->id;

        $query = Response::query()->with('period');

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        foreach (['directorate', 'unit', 'profession', 'status', 'tenure'] as $col) {
            if (! empty($filters[$col])) {
                $query->where($col, trim((string) $filters[$col]));
            }
        }

        $allResponses = $query->where(function ($q) {
            $q->whereNotNull('like_text')->orWhereNotNull('improve_text');
        })->get();

        $analysisResult = $this->analysisService->analyzeCollection($allResponses);
        $classifiedResponses = $analysisResult['classified_responses'];
        $totalWords = $analysisResult['total_words'];

        $kpis = $this->analyticsService->getKpiSummary($classifiedResponses, $totalWords);
        $topPositive = $this->keywordService->getTopKeywords($analysisResult['positive_frequencies'], 8);
        $topNegative = $this->keywordService->getTopKeywords($analysisResult['negative_frequencies'], 8);
        $insights = $this->insightService->generateInsights($kpis, $topPositive, $topNegative);

        $posWords = array_sum($analysisResult['positive_frequencies'] ?? []);
        $neuWords = array_sum($analysisResult['neutral_frequencies'] ?? []);
        $negWords = array_sum($analysisResult['negative_frequencies'] ?? []);
        $totalSentimentWords = $posWords + $neuWords + $negWords;

        $useWords = $totalSentimentWords > 0;
        $propTotal = $useWords ? $totalSentimentWords : $classifiedResponses->count();
        $propPos = $useWords ? $posWords : ($kpis['positive']['count'] ?? 0);
        $propNeu = $useWords ? $neuWords : ($kpis['neutral']['count'] ?? 0);
        $propNeg = $useWords ? $negWords : ($kpis['negative']['count'] ?? 0);
        $propUnit = $useWords ? 'kata' : 'respon';

        $propPosPct = $propTotal > 0 ? (int) round(($propPos / $propTotal) * 100) : 0;
        $propNeuPct = $propTotal > 0 ? (int) round(($propNeu / $propTotal) * 100) : 0;
        $propNegPct = $propTotal > 0 ? max(0, 100 - $propPosPct - $propNeuPct) : 0;

        $sentimentProportion = [
            'total' => $propTotal,
            'unit' => $propUnit,
            'positive' => ['count' => $propPos, 'percent' => $propPosPct],
            'neutral' => ['count' => $propNeu, 'percent' => $propNeuPct],
            'negative' => ['count' => $propNeg, 'percent' => $propNegPct],
            'series' => [$propPos, $propNeu, $propNeg],
        ];

        // Ambil kutipan representatif
        $positiveQuotes = $classifiedResponses->filter(fn ($r) => $r['sentiment'] === 'positive' && ! empty($r['like_text']))->take(3)->values();
        $negativeQuotes = $classifiedResponses->filter(fn ($r) => $r['sentiment'] === 'negative' && ! empty($r['improve_text']))->take(3)->values();

        return view('admin.sentiment.report', [
            'selectedPeriod' => $selectedPeriod,
            'kpis' => $kpis,
            'sentimentProportion' => $sentimentProportion,
            'topPositive' => $topPositive,
            'topNegative' => $topNegative,
            'insights' => $insights,
            'positiveQuotes' => $positiveQuotes,
            'negativeQuotes' => $negativeQuotes,
            'totalResponses' => $allResponses->count(),
            'generatedAt' => now()->translatedFormat('d F Y, H:i'),
        ]);
    }
}

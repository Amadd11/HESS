@extends('layouts.admin', ['title' => 'Sentiment Analysis Dashboard — HESS Admin'])

@section('header-title', 'Sentiment Analysis Dashboard')

@section('content')
<div class="space-y-6" x-data="sentimentDashboard({
    positiveWords: @js(array_map(fn($item) => [$item['word'], (int)$item['count']], $wordClouds['positive'] ?? [])),
    neutralWords: @js(array_map(fn($item) => [$item['word'], (int)$item['count']], $wordClouds['neutral'] ?? [])),
    negativeWords: @js(array_map(fn($item) => [$item['word'], (int)$item['count']], $wordClouds['negative'] ?? [])),
    sentimentSeries: @js($sentimentProportion['series'] ?? [0, 0, 0]),
    sentimentUnit: @js($sentimentProportion['unit'] ?? 'kata'),
    trendCategories: @js($trend['categories'] ?? []),
    trendSeries: @js($trend['series'] ?? []),
    unitCategories: @js($breakdown['unit']['categories'] ?? []),
    unitSeries: @js($breakdown['unit']['series'] ?? []),
    profCategories: @js($breakdown['profession']['categories'] ?? []),
    profSeries: @js($breakdown['profession']['series'] ?? []),
    statusLabels: @js($breakdown['status']['labels'] ?? []),
    statusSeries: @js($breakdown['status']['series'] ?? []),
    tenureCategories: @js($breakdown['tenure']['categories'] ?? []),
    tenureSeries: @js($breakdown['tenure']['series'] ?? []),
})" x-init="initDashboard()">

    {{-- 1. Header, Filters, & Export --}}
    @include('admin.sentiment.partials.header')

    @if(($kpis['total_feedback'] ?? 0) > 0)
        {{-- 2. 5 KPI Summary Cards --}}
        @include('admin.sentiment.partials.summary-cards')

        {{-- 3. Interactive Word Cloud with Tabs --}}
        @include('admin.sentiment.partials.word-cloud')

        {{-- 4. Baris Proporsi Sentimen, Top 10 Kata Kunci, & Contoh Kutipan (Sesuai Mockup) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            <!-- 4a. Donut Proporsi Sentimen -->
            <div class="lg:col-span-12 xl:col-span-4 flex flex-col">
                @include('admin.sentiment.partials.proportion-chart')
            </div>

            <!-- 4b. Top 10 Kata Positif & Negatif -->
            <div class="lg:col-span-12 xl:col-span-5 flex flex-col">
                @include('admin.sentiment.partials.keyword-chart')
            </div>

            <!-- 4c. Contoh Kutipan Responden -->
            <div class="lg:col-span-12 xl:col-span-3 flex flex-col">
                @include('admin.sentiment.partials.quotes-card')
            </div>
        </div>

        {{-- 5. Sentiment Trend Line Chart --}}
        @include('admin.sentiment.partials.trend-chart')

        {{-- 6. Multi-Dimensional Breakdown --}}
        @include('admin.sentiment.partials.breakdown-chart')

        {{-- 7. Feedback Explorer Table with Pagination & Search --}}
        @include('admin.sentiment.partials.feedback-table')

        {{-- 8. AI / Executive Insight Summary --}}
        @include('admin.sentiment.partials.ai-summary')
    @else
        {{-- Empty State jika data tidak ditemukan --}}
        @include('admin.sentiment.partials.empty-state')
    @endif

</div>
@endsection

@extends('layouts.admin', ['title' => 'Sentiment Analysis Dashboard — HESS Admin'])

@section('header-title', 'Sentiment Analysis Dashboard')

@section('content')
<div class="space-y-6" x-data="sentimentDashboard({
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

        {{-- 4. Top 10 Keywords Analytics --}}
        @include('admin.sentiment.partials.keyword-chart')

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

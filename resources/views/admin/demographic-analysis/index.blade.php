@extends('layouts.admin', ['title' => 'Analisis Demografi Responden — HESS Admin'])

@section('header-title', 'Analisis Karakteristik Demografi Pegawai')

@section('content')
<div x-data="demographicAnalysis({
    age: {{ Js::from($ageData) }},
    gender: {{ Js::from($genderData) }},
    income: {{ Js::from($incomeData) }},
    status: {{ Js::from($statusData) }},
    education: {{ Js::from($educationData) }},
    totalResponses: {{ (int) $totalResponses }}
})" x-init="initCharts()" class="space-y-4 sm:space-y-6">

    {{-- 1. Executive Demographic Header Banner --}}
    @include('admin.demographic-analysis.partials.header')

    {{-- 2. Multi-Dimensional Segmentation & Filter Toolbar --}}
    @include('admin.dashboard.partials.filter-toolbar')

    {{-- 3. 4 Executive KPI Metric Cards --}}
    @include('admin.demographic-analysis.partials.kpi-cards')

    {{-- 4. Grid Analisis Demografi: Usia & Jenis Kelamin --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        @include('admin.demographic-analysis.partials.distribution-card', ['data' => $ageData])
        @include('admin.demographic-analysis.partials.distribution-card', ['data' => $genderData])
    </div>

    {{-- 5. Grid Analisis Demografi: Pendapatan & Status Kepegawaian --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        @include('admin.demographic-analysis.partials.distribution-card', ['data' => $incomeData])
        @include('admin.demographic-analysis.partials.distribution-card', ['data' => $statusData])
    </div>

    {{-- 6. Grid Analisis Demografi: Pendidikan & Catatan Strategis RS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        @include('admin.demographic-analysis.partials.distribution-card', ['data' => $educationData])
    </div>

</div>
@endsection
@extends('layouts.admin', ['title' => 'Dashboard Analitik — HESS Admin'])

@section('header-title', 'Ringkasan & Analitik Kepuasan Pegawai')

@section('content')
<div class="space-y-6">

    {{-- 1. Periode Active & Executive Action Banner --}}
    @include('admin.dashboard.partials.banner-header')

    {{-- 2. Multi-Dimensional Segmentation & Filter Toolbar --}}
    @include('admin.dashboard.partials.filter-toolbar')

    {{-- 3. 4 Executive KPI Cards --}}
    @include('admin.dashboard.partials.kpi-cards')

    {{-- 4. Interactive Visual Charts --}}
    @include('admin.dashboard.partials.charts-section')

    {{-- 5. Actionable Intelligence: Top 5 Strengths vs Priority Areas --}}
    @include('admin.dashboard.partials.actionable-insights')

    {{-- 6. Detail 8 Faktor Kondisi & Lingkungan Kerja RS --}}
    @include('admin.dashboard.partials.hospital-dimensions')

    {{-- 7. Feedback Kualitatif Pegawai & Pencarian Aspirasi --}}
    @include('admin.dashboard.partials.qualitative-feedback')

</div>
@endsection

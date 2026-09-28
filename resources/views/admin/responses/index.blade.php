@extends('layouts.admin', ['title' => 'Data Respon Survei — HESS Admin'])

@section('header-title', 'Data Respon Survei Pegawai')

@section('content')
@php
    $hasActiveFilters = request()->anyFilled(['search', 'period_id', 'directorate', 'unit', 'profession', 'status', 'tenure', 'age', 'gender', 'education', 'income', 'nps_category']);
@endphp

<div x-data="responsesManager()" class="space-y-6">

    {{-- 1. Top Action Bar Header & Export Buttons --}}
    @include('admin.responses.partials.header')

    {{-- 2. 4 KPI Summary Cards (Total, Promoters, Passives, Detractors) --}}
    @include('admin.responses.partials.kpi-cards')

    {{-- 3. Quick Filter Tabs, Search Bar & Demographic Segmentation Toolbar --}}
    @include('admin.responses.partials.filter-toolbar')

    {{-- 4. Responses Data Table Card & Pagination --}}
    @include('admin.responses.partials.table')

    {{-- 5. Response Detail Modal --}}
    @include('admin.responses.partials.detail-modal')

</div>
@endsection
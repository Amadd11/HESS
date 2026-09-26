@extends('layouts.survey')

@section('content')
@if(!isset($period) || !$period || $questions->isEmpty())
<div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-200 text-center space-y-4 max-w-lg mx-auto my-12">
    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto text-amber-600 text-2xl font-bold">
        ⏸️
    </div>
    <h2 class="text-xl font-black text-gray-900">Belum Ada Periode Survei Aktif</h2>
    <p class="text-xs text-gray-500 leading-relaxed">
        Saat ini belum ada periode kuesioner survei kepuasan pegawai yang sedang dibuka oleh pihak rumah sakit. Silakan hubungi bagian manajemen atau administrator.
    </p>
</div>
@else
<div x-data="surveyWizard({{ Js::from($questions) }}, '{{ route('survey.submit') }}', '{{ csrf_token() }}')" class="space-y-4">

    {{-- 1. Header Progress Bar & Quick Controls --}}
    @include('survey.partials.progress-header')

    {{-- 2. Step 0: Pengantar Survei (Introduction) --}}
    @include('survey.partials.step-intro')

    {{-- 3. Step 1: Profil Pegawai --}}
    @include('survey.partials.step-profile')

    {{-- 3. Step 2: Kuesioner Kartu Pertanyaan (1 Soal per Layar) --}}
    @include('survey.partials.step-questions')

    {{-- 4. Step 2.5: Umpan Balik Aspek Kualitatif (Alasan & Saran per Unsur) --}}
    @include('survey.partials.step-aspect-feedback')

    {{-- 5. Step 3: Evaluasi Akhir & Konfirmasi --}}
    @include('survey.partials.step-overall')

    {{-- 5. Floating Bottom Navigation Bar --}}
    @include('survey.partials.navigation-footer')

    {{-- 6. Question Navigator Modal Grid --}}
    @include('survey.partials.navigator-modal')

</div>
@endif
@endsection
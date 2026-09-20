@extends('layouts.survey', ['title' => 'Terima Kasih — HESS'])

@section('content')
<div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-gray-200/75 text-center space-y-6 max-w-lg mx-auto my-6">
    <!-- Icon Check Sukses -->
    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto ring-8 ring-emerald-50/50">
        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <div class="space-y-2">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Survei Berhasil Dikirim</h2>
        <p class="text-sm text-gray-500 leading-relaxed">
            Terima kasih banyak atas waktu dan partisipasi Anda dalam mengisi kuesioner kepuasan kerja ini.
        </p>
    </div>

    <!-- Kotak Pesan Jaminan Anonimitas -->
    <div class="bg-primary-50/70 border border-primary-100 rounded-xl p-4 text-xs text-primary-900/90 text-left leading-relaxed flex items-start gap-3">
        <span class="text-lg leading-none">🛡️</span>
        <div>
            <strong>Anonimitas Terjaga:</strong> Seluruh jawaban Anda telah disimpan secara aman dan anonim tanpa identitas pribadi. Masukan Anda sangat berharga untuk meningkatkan kualitas lingkungan kerja dan pelayanan rumah sakit.
        </div>
    </div>

    <div class="pt-4">
        <a href="{{ route('survey.index') }}"
            class="inline-flex items-center justify-center h-11 px-6 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold transition">
            Kembali ke Halaman Awal
        </a>
    </div>
</div>
@endsection
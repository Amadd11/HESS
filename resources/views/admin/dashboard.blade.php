@extends('layouts.admin', ['title' => 'Dashboard Analitik — HESS Admin'])

@section('header-title', 'Ringkasan & Analitik Kepuasan Pegawai')

@section('content')
<div class="space-y-6">

    <!-- Periode Active Banner -->
    <div class="bg-gradient-to-r from-primary-800 via-primary-700 to-primary-600 rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <x-badge :color="$selectedPeriod?->is_active ? 'emerald' : 'gray'" size="xs" :dot="$selectedPeriod?->is_active ? true : false">
                        {{ $selectedPeriod?->is_active ? 'Periode Aktif' : 'Arsip Periode' }}
                    </x-badge>
                    <span class="text-xs text-primary-200/80">ID: #{{ $selectedPeriod?->id ?? '-' }}</span>
                </div>
                <h2 class="text-xl md:text-2xl font-black tracking-tight">
                    {{ $selectedPeriod?->name ?? 'Belum Ada Periode' }}
                </h2>
                <p class="text-xs md:text-sm text-primary-100/90 mt-1">
                    Rentang Waktu: {{ $selectedPeriod ? $selectedPeriod->start_date->format('d M Y') . ' — ' . $selectedPeriod->end_date->format('d M Y') : '-' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Dropdown Pilih Periode -->
                @if(isset($periods) && $periods->count() > 0)
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="inline-flex">
                        <select name="period_id" onchange="this.form.submit()"
                                class="h-10 px-3.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs border border-white/20 backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-white transition cursor-pointer">
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}" class="text-gray-900 font-semibold" {{ ($selectedPeriod?->id === $p->id) ? 'selected' : '' }}>
                                    {{ $p->name }} {{ $p->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <!-- Tombol Export CSV -->
                <x-button variant="emerald" size="md" :href="route('admin.dashboard.export', ['period_id' => $selectedPeriod?->id])">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh CSV</span>
                </x-button>

                <!-- Kelola Periode Link -->
                <x-button variant="ghost" size="md" :href="route('admin.periods.index')" class="bg-white/15 hover:bg-white/25 text-white border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Kelola Periode</span>
                </x-button>
            </div>
        </div>
    </div>

    <!-- 4 KPI Utama Kartu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Responden & Rate -->
        <x-stat-card
            title="Total Responden"
            :value="$totalResponses"
            :badge="$responseRate . '% Target'"
            badgeColor="emerald"
            :subtext="'Target: ' . $target . ' pegawai RS'"
            :progress="$responseRate"
            progressColor="bg-emerald-500"
        />

        <!-- General Satisfaction -->
        <x-stat-card
            title="Kepuasan Umum (MSQ)"
            :value="$avgGeneral . '%'"
            valueColor="text-primary-600"
            :badge="$avgGeneral >= 75 ? 'Puas' : 'Cukup'"
            :badgeColor="$avgGeneral >= 75 ? 'primary' : 'amber'"
            subtext="Rata-rata 20 Butir Instrumen MSQ"
            :progress="$avgGeneral"
            progressColor="bg-primary-600"
        />

        <!-- Faktor Rumah Sakit -->
        <x-stat-card
            title="Faktor Rumah Sakit"
            :value="$avgHospital . '%'"
            valueColor="text-blue-600"
            :badge="$hospitalCategoryScores->count() . ' Dimensi'"
            badgeColor="blue"
            subtext="Rata-rata Hospital Work Factors"
            :progress="$avgHospital"
            progressColor="bg-blue-600"
        />

        <!-- eNPS Score -->
        <x-stat-card
            title="Net Promoter Score (eNPS)"
            :value="($npsScore > 0 ? '+' : '') . $npsScore"
            :valueColor="$npsScore >= 0 ? 'text-emerald-600' : 'text-red-600'"
            badge="Skala -100 s/d +100"
            badgeColor="gray"
        >
            <div class="text-[11px] text-gray-400 flex items-center justify-between">
                <span>{{ $promoters }} Promoter</span>
                <span>{{ $passives }} Pasif</span>
                <span>{{ $detractors }} Detractor</span>
            </div>
            <div class="h-1.5 w-full bg-gray-100 rounded-full flex overflow-hidden">
                @if($totalResponses > 0)
                    <div class="bg-emerald-500 h-full" style="width: {{ ($promoters / $totalResponses) * 100 }}%"></div>
                    <div class="bg-amber-400 h-full" style="width: {{ ($passives / $totalResponses) * 100 }}%"></div>
                    <div class="bg-red-500 h-full" style="width: {{ ($detractors / $totalResponses) * 100 }}%"></div>
                @endif
            </div>
        </x-stat-card>
    </div>

    <!-- Breakdown Dimensi Faktor Rumah Sakit (8 Kategori Dinamis) -->
    <x-card>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4 mb-4">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-gray-900">Dimensi Lingkungan & Budaya Kerja Rumah Sakit</h3>
                    <x-badge color="blue">
                        {{ $hospitalCategoryScores->count() }} Dimensi Terdaftar
                    </x-badge>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Analisis skor rata-rata kepuasan pegawai per dimensi kerja operasional rumah sakit.</p>
            </div>
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-700 hover:text-primary-800 transition">
                <span>Kelola Master Kategori</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($hospitalCategoryScores as $cat)
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-primary-200 hover:shadow-md transition-all duration-200 space-y-3 flex flex-col justify-between group">
                    <div class="space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 text-primary-700 font-black text-xs group-hover:bg-primary-600 group-hover:text-white transition">
                                {{ $cat->code }}
                            </span>
                            <x-badge :color="($cat->percentage_score ?? 0) >= 75 ? 'emerald' : (($cat->percentage_score ?? 0) >= 65 ? 'primary' : 'amber')">
                                {{ ($cat->percentage_score ?? 0) >= 75 ? 'Optimal' : (($cat->percentage_score ?? 0) >= 65 ? 'Baik' : 'Perhatian') }}
                            </x-badge>
                        </div>
                        <h4 class="font-bold text-xs text-gray-900 group-hover:text-primary-700 transition line-clamp-2 min-h-[32px]">
                            {{ $cat->name }}
                        </h4>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <div class="flex items-baseline justify-between">
                            <span class="text-2xl font-black text-gray-900">{{ $cat->percentage_score ?? 0 }}%</span>
                            <span class="text-[11px] font-bold text-gray-400">{{ $cat->avg_raw_score ?? 0 }} / 5.0</span>
                        </div>
                        <div class="h-2 w-full bg-gray-200/80 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ ($cat->percentage_score ?? 0) >= 75 ? 'bg-emerald-500' : (($cat->percentage_score ?? 0) >= 65 ? 'bg-primary-600' : 'bg-amber-500') }}"
                                 style="width: {{ min($cat->percentage_score ?? 0, 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-gray-400 pt-1">
                            <span>{{ $cat->questions_count }} Butir Soal</span>
                            <a href="{{ route('admin.questions.index', ['category_id' => $cat->id]) }}"
                               class="text-primary-600 hover:text-primary-800 font-bold flex items-center gap-0.5">
                                <span>Detail Soal</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-6 text-center text-gray-400 text-xs">
                    Belum ada data kategori rumah sakit yang terdaftar.
                </div>
            @endforelse
        </div>
    </x-card>

    <!-- Analisis Intrinsik vs Ekstrinsik & Skor Unit -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Subskala MSQ Breakdown -->
        <x-card title="Perbandingan Dimensi Kepuasan MSQ">
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-gray-700">Kepuasan Intrinsik (12 Butir)</span>
                        <span class="text-primary-700">{{ $avgIntrinsic }}%</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mb-1.5">Kemandirian, makna kerja, moralitas, pelayanan pasien.</p>
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-600 rounded-full" style="width: {{ min($avgIntrinsic, 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-gray-700">Kepuasan Ekstrinsik (6 Butir)</span>
                        <span class="text-purple-700">{{ $avgExtrinsic }}%</span>
                    </div>
                    <p class="text-[11px] text-gray-400 mb-1.5">Supervisi atasan, gaji/remunerasi, promosi karir, pengakuan.</p>
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ min($avgExtrinsic, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-600 leading-relaxed border border-gray-100 mt-5">
                💡 <b>Insight Singkat:</b> Skor intrinsik mencerminkan kecintaan pegawai pada profesi medis/penunjang, sedangkan skor ekstrinsik mencerminkan kepuasan terhadap kebijakan manajemen rumah sakit.
            </div>
        </x-card>

        <!-- Tabel Peringkat Unit Kerja -->
        <x-card title="Tingkat Kepuasan per Unit Kerja / Instalasi" :subtitle="$unitScores->count() . ' Unit Terdata'" class="lg:col-span-2">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase">
                            <th class="py-2.5 px-3">Unit Kerja</th>
                            <th class="py-2.5 px-3 text-center">Responden</th>
                            <th class="py-2.5 px-3">Rata-rata Kepuasan</th>
                            <th class="py-2.5 px-3 text-right">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($unitScores as $row)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-2.5 px-3 font-bold text-gray-800">{{ $row->unit }}</td>
                                <td class="py-2.5 px-3 text-center text-gray-500">{{ $row->total }} pegawai</td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-gray-900 w-10">{{ $row->avg_score }}%</span>
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden max-w-[120px]">
                                            <div class="h-full rounded-full {{ $row->avg_score >= 80 ? 'bg-emerald-500' : ($row->avg_score >= 65 ? 'bg-primary-600' : 'bg-amber-500') }}"
                                                 style="width: {{ min($row->avg_score, 100) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <x-badge :color="$row->avg_score >= 80 ? 'emerald' : ($row->avg_score >= 65 ? 'primary' : 'amber')">
                                        {{ $row->avg_score >= 80 ? 'Sangat Puas' : ($row->avg_score >= 65 ? 'Puas' : 'Cukup') }}
                                    </x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">Belum ada respon yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>

    <!-- Feedback Kualitatif Masukan Terbuka Pegawai -->
    <x-card title="Masukan Kualitatif Pegawai (Anonim)" subtitle="Aspirasi jujur pegawai mengenai hal yang disukai dan hal yang paling perlu diperbaiki.">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($recentFeedbacks as $fb)
                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 space-y-3 text-xs">
                    <div class="flex items-center justify-between text-[11px] text-gray-500">
                        <x-badge color="primary">
                            {{ $fb->profession }} • Unit {{ $fb->unit }}
                        </x-badge>
                        <span>{{ $fb->completed_at ? $fb->completed_at->diffForHumans() : '-' }}</span>
                    </div>

                    @if($fb->like_text)
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Disukai</span>
                            <p class="text-gray-700 leading-relaxed font-medium">"{{ $fb->like_text }}"</p>
                        </div>
                    @endif

                    @if($fb->improve_text)
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider">Perlu Diperbaiki</span>
                            <p class="text-gray-700 leading-relaxed font-medium">"{{ $fb->improve_text }}"</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-2 py-8 text-center text-gray-400 text-xs">
                    Belum ada masukan kualitatif yang masuk.
                </div>
            @endforelse
        </div>
    </x-card>

</div>
@endsection

@extends('layouts.admin', ['title' => 'Master Demografi — HESS Admin'])

@section('header-title', 'Master Demografi Pegawai')

@section('content')
@php
    $hasActiveFilters = request()->anyFilled(['search', 'status']);
@endphp

<div x-data="demographicsManager('{{ $currentType }}')" class="space-y-6">

    <!-- Top Action Bar Header -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Opsi Demografi & Profil Responden</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola daftar satuan kerja rumah sakit, profesi pegawai, dan kriteria klasifikasi data kuesioner.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <x-button type="button" @click="openCreate('{{ $currentType === 'all' ? 'unit' : $currentType }}')" variant="primary" size="md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Opsi Demografi</span>
            </x-button>
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Satuan Kerja -->
        <a href="{{ route('admin.demographics.index', ['type' => 'unit']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ $currentType === 'unit' ? 'border-primary-400 ring-2 ring-primary-100 bg-primary-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-primary-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-primary-700 uppercase tracking-wider">Satuan Kerja RS</span>
                <x-badge color="primary" size="xs">Direktorat</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-primary-900">{{ $stats['unit'] }}</span>
                <span class="text-[11px] font-semibold text-primary-600">Kelompok Satker</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                <span>Direktorat & non-direktorat</span>
            </div>
        </a>

        <!-- Profesi Pegawai -->
        <a href="{{ route('admin.demographics.index', ['type' => 'profession']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ $currentType === 'profession' ? 'border-blue-400 ring-2 ring-blue-100 bg-blue-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-blue-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Profesi Pegawai</span>
                <x-badge color="blue" size="xs">Jabatan</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-blue-900">{{ $stats['profession'] }}</span>
                <span class="text-[11px] font-semibold text-blue-600">Kategori</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>Dokter, Perawat, Nakes, Staf</span>
            </div>
        </a>

        <!-- Status Kepegawaian -->
        <a href="{{ route('admin.demographics.index', ['type' => 'status']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ $currentType === 'status' ? 'border-amber-400 ring-2 ring-amber-100 bg-amber-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-amber-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Status Pegawai</span>
                <x-badge color="amber" size="xs">Ikatan Kerja</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-amber-900">{{ $stats['status'] }}</span>
                <span class="text-[11px] font-semibold text-amber-600">Kategori</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Tetap, Kontrak, dsb</span>
            </div>
        </a>

        <!-- Masa Kerja -->
        <a href="{{ route('admin.demographics.index', ['type' => 'tenure']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ $currentType === 'tenure' ? 'border-purple-400 ring-2 ring-purple-100 bg-purple-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-purple-300 transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Masa Kerja</span>
                <x-badge color="purple" size="xs">Rentang</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-purple-900">{{ $stats['tenure'] }}</span>
                <span class="text-[11px] font-semibold text-purple-600">Rentang Tahun</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                <span>Klasifikasi lama pengabdian</span>
            </div>
        </a>
    </div>

    <!-- Filter & Search Bar Container -->
    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-xs p-5 space-y-4">
        <!-- Quick Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-gray-100 scrollbar-none text-xs">
            <span class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider shrink-0 mr-1">Kelompok:</span>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'directorate'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'directorate' ? 'bg-indigo-700 text-white shadow-xs' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                Direktorat ({{ $stats['directorate'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'unit'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'unit' ? 'bg-primary-700 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Satuan Kerja ({{ $stats['unit'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'profession'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'profession' ? 'bg-blue-700 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                Profesi ({{ $stats['profession'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'status'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'status' ? 'bg-amber-700 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                Status ({{ $stats['status'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'tenure'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'tenure' ? 'bg-purple-700 text-white shadow-xs' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                Masa Kerja ({{ $stats['tenure'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'age'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'age' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                Usia ({{ $stats['age'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'gender'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'gender' ? 'bg-rose-700 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                Jenis Kelamin ({{ $stats['gender'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'education'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'education' ? 'bg-cyan-700 text-white shadow-xs' : 'bg-cyan-50 text-cyan-700 hover:bg-cyan-100' }}">
                Pendidikan ({{ $stats['education'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'income'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'income' ? 'bg-teal-700 text-white shadow-xs' : 'bg-teal-50 text-teal-700 hover:bg-teal-100' }}">
                Pendapatan ({{ $stats['income'] }})
            </a>

            <a href="{{ route('admin.demographics.index', array_merge(request()->except('type'), ['type' => 'all'])) }}"
               class="px-3.5 py-1.5 rounded-xl font-bold transition shrink-0 {{ $currentType === 'all' ? 'bg-gray-800 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua Opsi ({{ $stats['total'] }})
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.demographics.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif

            <!-- Search Input -->
            <div class="sm:col-span-8 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama unit kerja, divisi, atau profesi..."
                       class="w-full h-10 pl-10 pr-9 rounded-xl border border-gray-200 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
                @if(request('search'))
                    <a href="{{ route('admin.demographics.index', request()->except('search')) }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition" title="Hapus pencarian">
                        ✕
                    </a>
                @endif
            </div>

            <!-- Status Dropdown -->
            <div class="sm:col-span-2">
                <select name="status"
                        class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Submit & Reset Action -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <x-button type="submit" variant="primary" size="md" class="flex-1">
                    <span>Terapkan</span>
                </x-button>
                @if($hasActiveFilters)
                    <a href="{{ route('admin.demographics.index', ['type' => $currentType]) }}"
                       class="h-10 px-3 rounded-xl border border-gray-200 hover:border-gray-300 text-gray-500 hover:text-gray-800 text-xs font-bold flex items-center justify-center transition bg-white shrink-0"
                       title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Demographics Table Card -->
    <x-table-container class="border-gray-200/90 shadow-xs">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-500 font-extrabold text-[11px] uppercase tracking-wider select-none">
                    <th class="py-3.5 px-4 text-center w-16">Urutan</th>
                    <th class="py-3.5 px-4 w-36">Kelompok</th>
                    <th class="py-3.5 px-4 min-w-[240px]">Nama Opsi Demografi</th>
                    <th class="py-3.5 px-4 text-center w-36">Status Keaktifan</th>
                    <th class="py-3.5 px-4 text-right w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($demographics as $demo)
                    @php
                        $demoData = [
                            'id' => $demo->id,
                            'type' => $demo->type,
                            'name' => $demo->name,
                            'order' => $demo->order,
                            'is_active' => $demo->is_active ? 1 : 0,
                        ];
                    @endphp
                    <tr class="hover:bg-primary-50/20 transition-colors bg-white">
                        <!-- Urutan -->
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 font-mono text-xs font-bold text-gray-600">
                                {{ $demo->order }}
                            </span>
                        </td>

                        <!-- Kelompok -->
                        <td class="py-4 px-4">
                            @if($demo->type === 'directorate')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                    Direktorat
                                </span>
                            @elseif($demo->type === 'unit')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-primary-50 text-primary-700 border border-primary-200/80">
                                    Unit Kerja
                                </span>
                            @elseif($demo->type === 'profession')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80">
                                    Profesi
                                </span>
                            @elseif($demo->type === 'status')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/80">
                                    Status
                                </span>
                            @elseif($demo->type === 'tenure')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80">
                                    Masa Kerja
                                </span>
                            @elseif($demo->type === 'age')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    Usia
                                </span>
                            @elseif($demo->type === 'gender')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    Jenis Kelamin
                                </span>
                            @elseif($demo->type === 'education')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-cyan-50 text-cyan-700 border border-cyan-200/80">
                                    Pendidikan
                                </span>
                            @elseif($demo->type === 'income')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200/80">
                                    Pendapatan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200/80">
                                    {{ ucfirst($demo->type) }}
                                </span>
                            @endif
                        </td>

                        <!-- Nama Opsi -->
                        <td class="py-4 px-4">
                            <span class="font-bold text-gray-900 text-sm block">
                                {{ $demo->name }}
                            </span>
                        </td>

                        <!-- Status Keaktifan (with quick toggle) -->
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('admin.demographics.toggle', $demo->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition cursor-pointer {{ $demo->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                        title="Klik untuk mengubah status">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $demo->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    <span>{{ $demo->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </button>
                            </form>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Edit Button -->
                                <button type="button" @click="openEdit({{ Js::from($demoData) }})"
                                        class="p-2 text-gray-500 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition cursor-pointer"
                                        title="Ubah Opsi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.demographics.destroy', $demo->id) }}" method="POST" class="inline"
                                      data-title="Hapus Opsi Demografi?"
                                      data-confirm="Apakah Anda yakin ingin menghapus opsi '{{ $demo->name }}'?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer"
                                            title="Hapus Opsi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-800">Tidak ada opsi demografi</h4>
                                    <p class="text-xs text-gray-500">Coba sesuaikan kata kunci pencarian atau tambah opsi baru.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Table Pagination Footer -->
        <div class="p-4 md:px-6 border-t border-gray-100 bg-gray-50/50">
            {{ $demographics->links() }}
        </div>
    </x-table-container>

    <!-- Modals -->
    @include('admin.demographics.partials.create-modal')
    @include('admin.demographics.partials.edit-modal')

</div>
@endsection

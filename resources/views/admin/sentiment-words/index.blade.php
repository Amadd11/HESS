@extends('layouts.admin', ['title' => 'Master Kosakata Sentimen — HESS Admin'])

@section('header-title', 'Master Kosakata Sentimen')

@section('content')
@php
    $hasActiveFilters = request()->anyFilled(['search', 'sentiment', 'status']);
@endphp

<div x-data="sentimentWordsManager()" class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Kamus & Kosakata Analisis Sentimen</h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola istilah klinis, akronim RS, dan kamus leksikon kustom untuk analisis sentimen feedback kuesioner.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <x-button variant="secondary" size="md" :href="route('admin.sentiment.index')">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Dashboard Sentimen</span>
            </x-button>
            <x-button type="button" @click="createModalOpen = true" variant="primary" size="md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Kata Baru</span>
            </x-button>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Kosakata -->
        <a href="{{ route('admin.sentiment-words.index') }}"
           class="bg-white p-4 md:p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:border-primary-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Kosakata</span>
                <x-badge color="gray" size="xs">Leksikon</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-gray-900 group-hover:text-primary-700 transition">{{ number_format($stats['total']) }}</span>
                <span class="text-[11px] font-semibold text-gray-400">Kata Kunci</span>
            </div>
            <div class="mt-2 text-[11px] text-gray-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                <span>{{ number_format($stats['active']) }} kata aktif digunakan</span>
            </div>
        </a>

        <!-- Positif -->
        <a href="{{ route('admin.sentiment-words.index', ['sentiment' => 'positive']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('sentiment') === 'positive' ? 'border-emerald-400 ring-2 ring-emerald-100 bg-emerald-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-emerald-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Kata Positif</span>
                <x-badge color="emerald" size="xs">Pujian / Puas</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-emerald-700 group-hover:text-emerald-800 transition">{{ number_format($stats['positive']) }}</span>
                <span class="text-[11px] font-semibold text-emerald-600">Kosakata</span>
            </div>
            <div class="mt-2 text-[11px] text-emerald-600 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Mendorong skor sentimen positif</span>
            </div>
        </a>

        <!-- Netral -->
        <a href="{{ route('admin.sentiment-words.index', ['sentiment' => 'neutral']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('sentiment') === 'neutral' ? 'border-amber-400 ring-2 ring-amber-100 bg-amber-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-amber-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Kata Netral / SOP</span>
                <x-badge color="amber" size="xs">Operasional</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-amber-700 group-hover:text-amber-800 transition">{{ number_format($stats['neutral']) }}</span>
                <span class="text-[11px] font-semibold text-amber-600">Kosakata</span>
            </div>
            <div class="mt-2 text-[11px] text-amber-600 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Fasilitas, SOP & istilah kerja RS</span>
            </div>
        </a>

        <!-- Negatif -->
        <a href="{{ route('admin.sentiment-words.index', ['sentiment' => 'negative']) }}"
           class="bg-white p-4 md:p-5 rounded-2xl border {{ request('sentiment') === 'negative' ? 'border-rose-400 ring-2 ring-rose-100 bg-rose-50/20' : 'border-gray-200/90' }} shadow-xs hover:border-rose-300 hover:shadow-sm transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Kata Negatif</span>
                <x-badge color="rose" size="xs">Keluhan</x-badge>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl md:text-3xl font-black text-rose-700 group-hover:text-rose-800 transition">{{ number_format($stats['negative']) }}</span>
                <span class="text-[11px] font-semibold text-rose-600">Kosakata</span>
            </div>
            <div class="mt-2 text-[11px] text-rose-600 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                <span>Mendeteksi kendala & rasa ketidakpuasan</span>
            </div>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200/90 shadow-xs">
        <form action="{{ route('admin.sentiment-words.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 flex-1">
                <!-- Search Input -->
                <div class="relative min-w-[240px] flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kata atau catatan..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition outline-hidden">
                </div>

                <!-- Sentimen Filter -->
                <select name="sentiment" class="py-2 px-3 text-xs rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition outline-hidden">
                    <option value="">Semua Sentimen</option>
                    <option value="positive" {{ request('sentiment') === 'positive' ? 'selected' : '' }}>Positif</option>
                    <option value="neutral" {{ request('sentiment') === 'neutral' ? 'selected' : '' }}>Netral</option>
                    <option value="negative" {{ request('sentiment') === 'negative' ? 'selected' : '' }}>Negatif</option>
                </select>

                <!-- Status Filter -->
                <select name="status" class="py-2 px-3 text-xs rounded-xl border border-gray-200 bg-gray-50/50 focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition outline-hidden">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <x-button type="submit" variant="secondary" size="sm">
                    Terapkan
                </x-button>

                @if($hasActiveFilters)
                    <a href="{{ route('admin.sentiment-words.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <x-table-container>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50/75 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                    <th class="py-3 px-4 w-12 text-center">No</th>
                    <th class="py-3 px-4">Kata / Frasa</th>
                    <th class="py-3 px-4">Sentimen</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4">Catatan / Konteks</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs">
                @forelse($words as $index => $item)
                    <tr class="hover:bg-gray-50/60 transition group">
                        <td class="py-3 px-4 text-center text-gray-400 font-mono text-[11px]">
                            {{ $words->firstItem() + $index }}
                        </td>

                        <!-- Kata -->
                        <td class="py-3 px-4">
                            <span class="font-extrabold text-sm text-gray-900 font-mono group-hover:text-primary-700 transition">
                                {{ $item->word }}
                            </span>
                        </td>

                        <!-- Sentimen -->
                        <td class="py-3 px-4">
                            @if($item->sentiment === 'positive')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Positif</span>
                                </span>
                            @elseif($item->sentiment === 'negative')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Negatif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Netral</span>
                                </span>
                            @endif
                        </td>

                        <!-- Status Aktif / Toggle -->
                        <td class="py-3 px-4 text-center">
                            <form action="{{ route('admin.sentiment-words.toggle', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="cursor-pointer inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 border border-gray-200' }}"
                                        title="Klik untuk ubah status aktif">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    <span>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </button>
                            </form>
                        </td>

                        <!-- Catatan -->
                        <td class="py-3 px-4 text-gray-600 max-w-xs truncate">
                            {{ $item->notes ?? '—' }}
                        </td>

                        <!-- Aksi -->
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Edit Button -->
                                <button type="button" @click="openEdit({{ Js::from($item) }})"
                                        class="p-2 text-gray-500 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition border border-transparent hover:border-primary-100 cursor-pointer"
                                        title="Edit Kata">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.sentiment-words.destroy', $item->id) }}" method="POST" class="inline"
                                      data-title="Hapus Kata Kamus Sentimen?"
                                      data-confirm="Apakah Anda yakin ingin menghapus kata '{{ $item->word }}' dari kamus sentimen?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition border border-transparent hover:border-red-100 cursor-pointer"
                                            title="Hapus Kata">
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
                        <td colspan="7" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-gray-800">Belum ada kosakata kustom</h4>
                                    <p class="text-xs text-gray-500">Kamus leksikon bawaan sistem tetap aktif. Anda dapat menambahkan kata/akronim khusus rumah sakit di sini.</p>
                                </div>
                                <button type="button" @click="createModalOpen = true"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Tambah Kata Pertama</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Table Count & Pagination Footer -->
        <div class="p-4 md:px-6 md:py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
            <div>
                Menampilkan <span class="font-bold text-gray-800">{{ $words->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-800">{{ $words->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-800">{{ $words->total() }}</span> kosakata
            </div>
            <div>
                {{ $words->links() }}
            </div>
        </div>
    </x-table-container>

    <!-- CREATE MODAL -->
    <x-modal show="createModalOpen" title="Tambah Kosakata Sentimen Baru" maxWidth="max-w-md">
        <form action="{{ route('admin.sentiment-words.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <x-input
                label="Kata / Istilah / Akronim"
                name="word"
                placeholder="Contoh: rme, lembur, apresiasi, cssd"
                :required="true"
            />

            <x-select label="Sentimen" name="sentiment" :required="true">
                <option value="positive">Positif (Pujian / Puas)</option>
                <option value="neutral">Netral (SOP / Operasional)</option>
                <option value="negative">Negatif (Keluhan / Hambatan)</option>
            </x-select>

            <x-input
                label="Catatan / Konteks (Opsional)"
                name="notes"
                placeholder="Contoh: Akronim Rekam Medis Elektronik"
            />

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="create_is_active" value="1" checked
                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                <label for="create_is_active" class="text-xs font-medium text-gray-700 select-none">
                    Aktifkan kata ini untuk analisis sentimen
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="createModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Simpan Kosakata
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- EDIT MODAL -->
    <x-modal show="editModalOpen" title="Edit Kosakata Sentimen" maxWidth="max-w-md">
        <form :action="'{{ url('admin/sentiment-words') }}/' + (editingWord?.id ?? '')" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Kata / Istilah / Akronim <span class="text-red-500">*</span></label>
                <input type="text" name="word" x-model="editingWord.word" required
                       class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-hidden font-mono">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Sentimen <span class="text-red-500">*</span></label>
                <select name="sentiment" x-model="editingWord.sentiment" required
                        class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-hidden">
                    <option value="positive">Positif</option>
                    <option value="neutral">Netral</option>
                    <option value="negative">Negatif</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Catatan / Konteks</label>
                <input type="text" name="notes" x-model="editingWord.notes"
                       placeholder="Contoh: Akronim Rekam Medis Elektronik"
                       class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-hidden">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editingWord?.is_active"
                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                <label for="edit_is_active" class="text-xs font-medium text-gray-700 select-none">
                    Aktifkan kata ini untuk analisis sentimen
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="editModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

</div>

<script>
    function sentimentWordsManager() {
        return {
            createModalOpen: false,
            editModalOpen: false,
            editingWord: {
                id: null,
                word: '',
                sentiment: 'positive',
                is_active: true,
                notes: ''
            },
            openEdit(word) {
                this.editingWord = Object.assign({}, word);
                this.editModalOpen = true;
            }
        };
    }
</script>
@endsection

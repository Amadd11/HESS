@extends('layouts.admin', ['title' => 'Periode Survei — HESS Admin'])

@section('header-title', 'Manajemen Periode Survei')

@section('content')
<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editPeriod: {
        id: '',
        name: '',
        target: 250,
        start_date: '',
        end_date: '',
        is_active: false
    },
    openEdit(p) {
        this.editPeriod = {
            id: p.id,
            name: p.name,
            target: p.target,
            start_date: p.start_date_formatted || '',
            end_date: p.end_date_formatted || '',
            is_active: Boolean(p.is_active)
        };
        this.editModalOpen = true;
    }
}" class="space-y-6">

    <!-- Top Action Bar -->
    <x-page-header title="Siklus & Periode Pelaksanaan Survei" description="Atur target capaian responden, tanggal mulai/selesai, serta aktivasi periode survei kuesioner.">
        <x-button type="button" @click="createModalOpen = true" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Buka Periode Baru</span>
        </x-button>
    </x-page-header>

    <!-- Periods Table Card -->
    <x-table-container>
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-500 font-extrabold uppercase tracking-wider">
                    <th class="py-3.5 px-4">Nama Periode</th>
                    <th class="py-3.5 px-4 w-48">Capaian Responden</th>
                    <th class="py-3.5 px-4 w-52">Rentang Tanggal</th>
                    <th class="py-3.5 px-4 w-36 text-center">Status</th>
                    <th class="py-3.5 px-4 text-right w-44">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periods as $period)
                    @php
                        $rate = $period->target > 0 ? round(($period->responses_count / $period->target) * 100, 1) : 0;
                        $periodData = [
                            'id' => $period->id,
                            'name' => $period->name,
                            'target' => $period->target,
                            'start_date_formatted' => $period->start_date?->format('Y-m-d'),
                            'end_date_formatted' => $period->end_date?->format('Y-m-d'),
                            'is_active' => $period->is_active,
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition {{ $period->is_active ? 'bg-primary-50/20' : '' }}">
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                <span>{{ $period->name }}</span>
                                @if($period->is_active)
                                    <x-badge color="emerald" size="xs">
                                        LIVE
                                    </x-badge>
                                @endif
                            </div>
                            <div class="text-[11px] text-gray-400 mt-0.5">Slug: {{ $period->slug }}</div>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="flex items-center justify-between text-xs font-bold mb-1">
                                <span class="text-gray-800">{{ $period->responses_count }} / {{ $period->target }}</span>
                                <span class="{{ $rate >= 100 ? 'text-emerald-600' : 'text-primary-600' }}">{{ $rate }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $rate >= 100 ? 'bg-emerald-500' : 'bg-primary-600' }}" style="width: {{ min($rate, 100) }}%"></div>
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-gray-600">
                            <div class="font-medium text-xs">{{ $period->start_date?->format('d M Y') }} — {{ $period->end_date?->format('d M Y') }}</div>
                            @if($period->end_date && $period->end_date->isPast())
                                <span class="text-[10px] text-gray-400">Telah berakhir</span>
                            @elseif($period->start_date && $period->start_date->isFuture())
                                <span class="text-[10px] text-amber-600 font-medium">Akan datang</span>
                            @else
                                <span class="text-[10px] text-emerald-600 font-bold">Sedang Berlangsung</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            @if($period->is_active)
                                <x-badge color="emerald" size="sm" :dot="true">
                                    Aktif
                                </x-badge>
                            @else
                                <x-badge color="gray" size="sm">
                                    Arsip
                                </x-badge>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-right space-x-1">
                            @if(!$period->is_active)
                                <!-- Tombol Jadikan Aktif -->
                                <form action="{{ route('admin.periods.activate', $period->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Jadikan Periode Aktif"
                                            class="px-2.5 py-1 text-[11px] font-bold text-primary-700 bg-primary-50 hover:bg-primary-100 rounded-lg transition cursor-pointer">
                                        Aktifkan
                                    </button>
                                </form>
                            @endif

                            <!-- Lihat Analitik -->
                            <a href="{{ route('admin.dashboard', ['period_id' => $period->id]) }}"
                               class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block cursor-pointer" title="Lihat Dashboard Analitik">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </a>

                            <!-- Edit Button -->
                            <button type="button" @click="openEdit({{ Js::from($periodData) }})"
                                    class="p-1.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition cursor-pointer" title="Edit Periode">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            <!-- Delete Button -->
                            @if(!$period->is_active)
                                <form action="{{ route('admin.periods.destroy', $period->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Hapus Periode">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 font-medium">
                            Belum ada periode survei yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-container>

    <!-- MODAL CREATE PERIODE -->
    <x-modal show="createModalOpen" title="Buka Periode Survei Baru" maxWidth="max-w-lg">
        <form action="{{ route('admin.periods.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <x-input
                label="Nama Periode Survei"
                name="name"
                placeholder="Contoh: Survei Kepuasan Pegawai 2026 — Semester 2"
                :required="true"
            />

            <x-input
                label="Target Responden (Jumlah Pegawai)"
                name="target"
                type="number"
                value="250"
                min="1"
                :required="true"
            />

            <div class="grid grid-cols-2 gap-3">
                <x-input
                    label="Tanggal Mulai"
                    name="start_date"
                    type="date"
                    :value="date('Y-m-d')"
                    :required="true"
                />
                <x-input
                    label="Tanggal Selesai"
                    name="end_date"
                    type="date"
                    :value="date('Y-m-d', strtotime('+30 days'))"
                    :required="true"
                />
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1"
                           class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500">
                    <span class="text-xs font-semibold text-gray-700">Langsung jadikan sebagai periode aktif saat ini</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="createModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Simpan & Buka Periode
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL EDIT PERIODE -->
    <x-modal show="editModalOpen" title="Edit Periode Survei" maxWidth="max-w-lg">
        <form :action="'/admin/periods/' + editPeriod.id" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <x-input
                label="Nama Periode Survei"
                name="name"
                x-model="editPeriod.name"
                :required="true"
            />

            <x-input
                label="Target Responden (Jumlah Pegawai)"
                name="target"
                type="number"
                x-model.number="editPeriod.target"
                min="1"
                :required="true"
            />

            <div class="grid grid-cols-2 gap-3">
                <x-input
                    label="Tanggal Mulai"
                    name="start_date"
                    type="date"
                    x-model="editPeriod.start_date"
                    :required="true"
                />
                <x-input
                    label="Tanggal Selesai"
                    name="end_date"
                    type="date"
                    x-model="editPeriod.end_date"
                    :required="true"
                />
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" :checked="editPeriod.is_active"
                           class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500">
                    <span class="text-xs font-semibold text-gray-700">Jadikan sebagai periode aktif saat ini</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="editModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Perbarui Periode
                </x-button>
            </div>
        </form>
    </x-modal>

</div>
@endsection

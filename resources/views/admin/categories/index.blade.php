@extends('layouts.admin', ['title' => 'Master Kategori — HESS Admin'])

@section('header-title', 'Master Kategori Kuesioner')

@section('content')
<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    editCategory: {
        id: '',
        name: '',
        code: '',
        type: 'hospital',
        order: 1
    },
    openEdit(cat) {
        this.editCategory = {
            id: cat.id,
            name: cat.name,
            code: cat.code,
            type: cat.type,
            order: cat.order
        };
        this.editModalOpen = true;
    }
}" class="space-y-6">

    <!-- Top Action Bar -->
    <x-page-header title="Pengelompokan Dimensi & Faktor Kuesioner" description="Kelola kategori baku MSQ-20 dan dimensi faktor lingkungan rumah sakit.">
        <x-button type="button" @click="createModalOpen = true" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Kategori Baru</span>
        </x-button>
    </x-page-header>

    <!-- Categories Table Card -->
    <x-table-container>
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-500 font-extrabold uppercase tracking-wider">
                    <th class="py-3.5 px-4 w-16 text-center">Urutan</th>
                    <th class="py-3.5 px-4 w-24">Kode</th>
                    <th class="py-3.5 px-4">Nama Kategori / Dimensi</th>
                    <th class="py-3.5 px-4">Kelompok Instrumen</th>
                    <th class="py-3.5 px-4 text-center">Jumlah Soal Terdaftar</th>
                    <th class="py-3.5 px-4 text-right w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-400">{{ $cat->order }}</td>
                        <td class="py-3.5 px-4">
                            <span class="font-extrabold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md">
                                {{ $cat->code }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-gray-900 text-sm">
                            {{ $cat->name }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($cat->type === 'msq')
                                <x-badge color="purple">
                                    MSQ-20 Instrument
                                </x-badge>
                            @else
                                <x-badge color="blue">
                                    Hospital Work Factor
                                </x-badge>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('admin.questions.index', ['category_id' => $cat->id]) }}"
                               class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold text-gray-700 bg-gray-100 hover:bg-primary-50 hover:text-primary-700 transition" title="Lihat soal di kategori ini">
                                {{ $cat->questions_count }} Soal
                            </a>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            <!-- Edit Button -->
                            <button type="button" @click="openEdit({{ Js::from($cat) }})"
                                    class="p-1.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition cursor-pointer" title="Edit Kategori">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Hapus Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 font-medium">
                            Belum ada kategori yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-container>

    <!-- MODAL CREATE KATEGORI -->
    <x-modal show="createModalOpen" title="Tambah Kategori Baru" maxWidth="max-w-md">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <x-input
                label="Nama Kategori / Dimensi"
                name="name"
                placeholder="Contoh: Communication & Coordination"
                :required="true"
            />

            <div class="grid grid-cols-2 gap-3">
                <x-input
                    label="Kode Kategori"
                    name="code"
                    placeholder="Contoh: CC"
                    :required="true"
                />
                <x-input
                    label="Nomor Urutan"
                    name="order"
                    type="number"
                    :value="\App\Models\Category::max('order') + 1"
                />
            </div>

            <x-select label="Kelompok Instrumen" name="type" :required="true">
                <option value="hospital">Hospital Work Factor (Faktor RS)</option>
                <option value="msq">MSQ-20 (Minnesota Satisfaction)</option>
            </x-select>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="createModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Simpan Kategori
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- MODAL EDIT KATEGORI -->
    <x-modal show="editModalOpen" title="Edit Kategori" maxWidth="max-w-md">
        <form :action="'/admin/categories/' + editCategory.id" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <x-input
                label="Nama Kategori / Dimensi"
                name="name"
                x-model="editCategory.name"
                :required="true"
            />

            <div class="grid grid-cols-2 gap-3">
                <x-input
                    label="Kode Kategori"
                    name="code"
                    x-model="editCategory.code"
                    :required="true"
                />
                <x-input
                    label="Nomor Urutan"
                    name="order"
                    type="number"
                    x-model="editCategory.order"
                />
            </div>

            <x-select label="Kelompok Instrumen" name="type" x-model="editCategory.type" :required="true">
                <option value="hospital">Hospital Work Factor (Faktor RS)</option>
                <option value="msq">MSQ-20 (Minnesota Satisfaction)</option>
            </x-select>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <x-button type="button" @click="editModalOpen = false" variant="secondary">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Perbarui Kategori
                </x-button>
            </div>
        </form>
    </x-modal>

</div>
@endsection

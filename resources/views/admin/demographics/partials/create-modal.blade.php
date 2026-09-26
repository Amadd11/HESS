<!-- Create Demographic Modal -->
<div x-show="createModalOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
     role="dialog" aria-modal="true">

    <!-- Backdrop -->
    <div x-show="createModalOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="createModalOpen = false"
         class="fixed inset-0 bg-gray-900/60 transition-opacity"></div>

    <!-- Modal Card -->
    <div x-show="createModalOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden z-10 my-8">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Tambah Opsi Demografi Baru</h3>
                    <p class="text-[11px] text-gray-500">Tambahkan pilihan satuan kerja, profesi, atau kriteria lainnya.</p>
                </div>
            </div>

            <button type="button" @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('admin.demographics.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label for="create_type" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Kelompok Demografi <span class="text-red-500">*</span>
                </label>
                <select name="type" id="create_type" x-model="createData.type" required
                        class="w-full h-10 px-3.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="unit">Satuan Kerja / Sub-unit RS</option>
                    <option value="directorate">Direktorat</option>
                    <option value="profession">Profesi Pegawai</option>
                    <option value="status">Status Kepegawaian</option>
                    <option value="tenure">Masa Kerja</option>
                    <option value="age">Rentang Usia</option>
                    <option value="gender">Jenis Kelamin</option>
                    <option value="education">Latar Belakang Pendidikan</option>
                    <option value="income">Jumlah Pendapatan</option>
                </select>
            </div>

            <div>
                <x-input
                    label="Nama Opsi / Kategori"
                    name="name"
                    type="text"
                    x-model="createData.name"
                    required
                    placeholder="Contoh: Instalasi Rawat Jalan, Dokter Spesialis..."
                />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input
                        label="Nomor Urutan Tampilan"
                        name="order"
                        type="number"
                        x-model="createData.order"
                        min="0"
                        placeholder="0"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Status Keaktifan
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer mt-2 text-xs font-medium text-gray-800">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                        <span>Aktif (Tampil di Kuesioner)</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <x-button type="button" @click="createModalOpen = false" variant="secondary" size="md">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" size="md">
                    Simpan Opsi
                </x-button>
            </div>
        </form>
    </div>
</div>

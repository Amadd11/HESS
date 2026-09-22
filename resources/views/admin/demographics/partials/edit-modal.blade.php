<!-- Edit Demographic Modal -->
<div x-show="editModalOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
     role="dialog" aria-modal="true">

    <!-- Backdrop -->
    <div x-show="editModalOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="editModalOpen = false"
         class="fixed inset-0 bg-gray-900/60 transition-opacity"></div>

    <!-- Modal Card -->
    <div x-show="editModalOpen"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Ubah Opsi Demografi</h3>
                    <p class="text-[11px] text-gray-500">Perbarui nama atau urutan opsi demografi.</p>
                </div>
            </div>

            <button type="button" @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Form Body -->
        <form :action="'/admin/demographics/' + editDemographic.id" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Kelompok Demografi
                </label>
                <div class="px-3.5 py-2.5 rounded-xl bg-gray-100 border border-gray-200 text-xs font-bold text-gray-700 uppercase"
                     x-text="editDemographic.type"></div>
            </div>

            <div>
                <label for="edit_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nama Opsi / Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="edit_name" x-model="editDemographic.name" required
                       class="w-full h-10 px-3.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_order" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Urutan Tampilan
                    </label>
                    <input type="number" name="order" id="edit_order" x-model="editDemographic.order" min="0"
                           class="w-full h-10 px-3.5 rounded-xl border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Status Keaktifan
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer mt-2 text-xs font-medium text-gray-800">
                        <input type="checkbox" name="is_active" value="1" :checked="editDemographic.is_active == 1"
                               class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                        <span>Aktif (Tampil)</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <x-button type="button" @click="editModalOpen = false" variant="secondary" size="md">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" size="md">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</div>

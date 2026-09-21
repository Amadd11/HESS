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
                :value="$nextOrder ?? 1"
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

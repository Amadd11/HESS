<!-- MODAL EDIT KATEGORI -->
<x-modal show="editModalOpen" title="Edit Kategori" maxWidth="max-w-md">
    <form :action="'{{ url('admin/categories') }}/' + editCategory.id" method="POST" class="space-y-4 text-xs">
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

<!-- MODAL EDIT PERTANYAAN -->
<x-modal show="editModalOpen" title="Edit Butir Pertanyaan" maxWidth="max-w-lg">
    <form :action="'{{ url('admin/questions') }}/' + editQuestion.id" method="POST" class="space-y-4 text-xs">
        @csrf
        @method('PUT')

        <x-select label="Indikator Instrumen" name="category_id" x-model="editQuestion.category_id" :required="true">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
            @endforeach
        </x-select>

        <div class="grid grid-cols-2 gap-3">
            <x-input
                label="Kode Soal"
                name="code"
                x-model="editQuestion.code"
                :required="true"
            />
            <x-input
                label="Nomor Urutan"
                name="order"
                type="number"
                x-model="editQuestion.order"
            />
        </div>

        <x-textarea
            label="Teks Pernyataan"
            name="text"
            rows="3"
            x-model="editQuestion.text"
            :required="true"
        />


        <div class="flex items-center gap-2 pt-1">
            <input type="checkbox" name="is_active" value="1" :checked="editQuestion.is_active" id="edit_active" class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500 cursor-pointer">
            <label for="edit_active" class="text-gray-700 font-semibold cursor-pointer">Pertanyaan Aktif di Kuesioner</label>
        </div>

        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
            <x-button type="button" @click="editModalOpen = false" variant="secondary">
                Batal
            </x-button>
            <x-button type="submit" variant="primary">
                Perbarui Pertanyaan
            </x-button>
        </div>
    </form>
</x-modal>

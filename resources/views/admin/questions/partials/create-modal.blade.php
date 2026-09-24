<!-- MODAL CREATE PERTANYAAN -->
<x-modal show="createModalOpen" title="Tambah Butir Pertanyaan Baru" maxWidth="max-w-lg">
    <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-4 text-xs">
        @csrf

        <x-select label="Indikator Instrumen" name="category_id" :required="true">
            <option value="">Pilih Indikator</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
            @endforeach
        </x-select>

        <div class="grid grid-cols-2 gap-3">
            <x-input
                label="Kode Soal"
                name="code"
                placeholder="Contoh: H25"
                :required="true"
            />
            <x-input
                label="Nomor Urutan"
                name="order"
                type="number"
                :value="$nextOrder ?? 1"
            />
        </div>

        <x-textarea
            label="Teks Pernyataan"
            name="text"
            rows="3"
            placeholder="Tuliskan butir pernyataan kuesioner..."
            :required="true"
        />

        <div>
            <x-select label="Tipe Skala Pilihan" name="scale" :required="true">
                <option value="agreement" selected>Setuju (1: Sangat Tidak Setuju - 4: Sangat Setuju)</option>
                <option value="satisfaction">Puas (1: Sangat Tidak Puas - 4: Sangat Puas)</option>
            </x-select>
        </div>

        <div class="flex items-center gap-2 pt-1">
            <input type="checkbox" name="is_active" value="1" checked id="create_active" class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500 cursor-pointer">
            <label for="create_active" class="text-gray-700 font-semibold cursor-pointer">Langsung Aktifkan Pertanyaan di Kuesioner</label>
        </div>

        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
            <x-button type="button" @click="createModalOpen = false" variant="secondary">
                Batal
            </x-button>
            <x-button type="submit" variant="primary">
                Simpan Pertanyaan
            </x-button>
        </div>
    </form>
</x-modal>

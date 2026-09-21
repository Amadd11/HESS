<!-- MODAL CREATE PERTANYAAN -->
<x-modal show="createModalOpen" title="Tambah Butir Pertanyaan Baru" maxWidth="max-w-lg">
    <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-4 text-xs">
        @csrf

        <x-select label="Kategori Instrumen" name="category_id" :required="true">
            <option value="">-- Pilih Kategori --</option>
            <optgroup label="Instrumen Baku MSQ-20">
                @foreach($categories->where('type', 'msq') as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                @endforeach
            </optgroup>
            <optgroup label="Faktor Kerja Rumah Sakit">
                @foreach($categories->where('type', 'hospital') as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                @endforeach
            </optgroup>
        </x-select>

        <div class="grid grid-cols-2 gap-3">
            <x-input
                label="Kode Soal"
                name="code"
                placeholder="Contoh: MSQ21 atau H25"
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

        <div class="grid grid-cols-2 gap-3">
            <x-select label="Tipe Skala Pilihan" name="scale" :required="true">
                <option value="satisfaction">Puas (Sangat Tidak Puas - Sangat Puas)</option>
                <option value="agreement">Setuju (Sangat Tidak Setuju - Sangat Setuju)</option>
            </x-select>

            <x-select label="Subskala (Khusus MSQ)" name="subscale">
                <option value="">-- Tidak Ada / Faktor RS --</option>
                <option value="intrinsic">Kepuasan Intrinsik</option>
                <option value="extrinsic">Kepuasan Ekstrinsik</option>
                <option value="general">Kepuasan Umum</option>
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

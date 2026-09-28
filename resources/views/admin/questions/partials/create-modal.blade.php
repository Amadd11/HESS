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
            <div>
                <x-input
                    label="Kode Soal (Otomatis)"
                    name="code"
                    id="create_code_input"
                    :value="$nextCode ?? 'H1'"
                    readonly
                    class="bg-gray-50 text-gray-700 font-mono font-bold cursor-not-allowed select-none border-gray-200"
                    :required="true" />
                <p class="text-[10px] text-gray-400 mt-1">Kode identitas permanen otomatis dari sistem.</p>
            </div>
            <div>
                <x-input
                    label="Nomor Urutan Tampil"
                    name="order"
                    id="create_order_input"
                    type="number"
                    min="1"
                    :value="$nextOrder ?? 1" />
                <p class="text-[10px] text-gray-400 mt-1">Mengatur posisi nomor butir di kuesioner.</p>
            </div>
        </div>


        <!-- Banner Info Auto-Shift Urutan -->
        <div class="p-3 rounded-xl bg-blue-50/80 border border-blue-200/70 text-[11px] text-blue-800 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="leading-relaxed">
                <span class="font-bold text-blue-900">Pergeseran Urutan Tampil Otomatis:</span>
                Jika nomor urutan tampil yang dipilih sudah terpakai (misal nomor <strong>21</strong>), sistem otomatis memundurkan urutan butir-butir setelahnya (+1). Kode internal masing-masing butir tetap aman dan tidak berubah.
            </div>
        </div>


        <x-textarea
            label="Teks Pernyataan"
            name="text"
            rows="3"
            placeholder="Tuliskan butir pernyataan kuesioner..."
            :required="true" />


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
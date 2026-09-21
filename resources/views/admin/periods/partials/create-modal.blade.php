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

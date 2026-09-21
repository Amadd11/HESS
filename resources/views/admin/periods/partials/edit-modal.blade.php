<!-- MODAL EDIT PERIODE -->
<x-modal show="editModalOpen" title="Edit Periode Survei" maxWidth="max-w-lg">
    <form :action="'/admin/periods/' + editPeriod.id" method="POST" class="space-y-4 text-xs">
        @csrf
        @method('PUT')

        <x-input
            label="Nama Periode Survei"
            name="name"
            x-model="editPeriod.name"
            :required="true"
        />

        <x-input
            label="Target Responden (Jumlah Pegawai)"
            name="target"
            type="number"
            x-model.number="editPeriod.target"
            min="1"
            :required="true"
        />

        <div class="grid grid-cols-2 gap-3">
            <x-input
                label="Tanggal Mulai"
                name="start_date"
                type="date"
                x-model="editPeriod.start_date"
                :required="true"
            />
            <x-input
                label="Tanggal Selesai"
                name="end_date"
                type="date"
                x-model="editPeriod.end_date"
                :required="true"
            />
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" :checked="editPeriod.is_active"
                       class="w-4 h-4 rounded text-primary-600 border-gray-300 focus:ring-primary-500">
                <span class="text-xs font-semibold text-gray-700">Jadikan sebagai periode aktif saat ini</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
            <x-button type="button" @click="editModalOpen = false" variant="secondary">
                Batal
            </x-button>
            <x-button type="submit" variant="primary">
                Perbarui Periode
            </x-button>
        </div>
    </form>
</x-modal>

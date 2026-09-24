<?php

namespace Database\Seeders;

use App\Models\Demographic;
use Illuminate\Database\Seeder;

class DemographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = [
            'Medis',
            'Penunjang Medis',
            'Perawat dan Bidan',
            'Non Medis',
        ];

        $directorates = [
            'Direktorat Pelayanan Medis',
            'Direktorat Keperawatan',
            'Direktorat Penunjang Medis',
            'Direktorat SDM',
            'Direktorat Keuangan',
            'Direktorat Umum',
            'Direktorat lainnya',
        ];

        $units = [
            'Instalasi Gawat Darurat',
            'Instalasi Rawat Jalan',
            'Instalasi Rawat Inap',
            'Instalasi Bedah Sentral',
            'Instalasi ICU / Perawatan Intensif',
            'Instalasi Pelayanan Jantung',
            'Instalasi Pelayanan Anak',
            'Instalasi Pelayanan Ibu dan Anak',
            'Instalasi Farmasi',
            'Instalasi Laboratorium',
            'Instalasi Radiologi',
            'Instalasi Gizi',
            'Instalasi Rehabilitasi Medik',
            'Instalasi Rekam Medis',
            'Instalasi Keperawatan',
            'Unit SDM',
            'Unit Pendidikan dan Pelatihan',
            'Unit Keuangan / Akuntansi',
            'Unit Pengadaan',
            'Unit Teknologi Informasi',
            'Unit Sarana dan Prasarana',
            'Unit Rumah Tangga',
            'Unit Hukum / Humas',
            'Unit lainnya',
        ];

        $statuses = [
            'Pegawai Tetap',
            'Pegawai Kontrak',
            'Outsourcing',
            'Paruh Waktu',
            'Lainnya',
        ];

        $tenures = [
            '< 1 tahun',
            '1–3 tahun',
            '4–5 tahun',
            '6–10 tahun',
            '> 10 tahun',
        ];

        // Deactivate all previous demographics first to sync cleanly
        Demographic::query()->update(['is_active' => false]);

        $syncDemographics = function (string $type, array $names): void {
            $order = 1;
            foreach ($names as $name) {
                Demographic::updateOrCreate(
                    ['type' => $type, 'name' => $name],
                    ['order' => $order++, 'is_active' => true]
                );
            }
        };

        $syncDemographics('profession', $professions);
        $syncDemographics('directorate', $directorates);
        $syncDemographics('unit', $units);
        $syncDemographics('status', $statuses);
        $syncDemographics('tenure', $tenures);
    }
}

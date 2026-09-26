<?php

namespace Database\Seeders;

use App\Models\Demographic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DemographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = [
            'Dokter / Medis',
            'Penunjang medis',
            'Perawat dan bidan',
            'Non medis',
        ];

        $directorates = [
            'Direktorat Medik dan Keperawatan',
            'Direktorat SDM, Pendidikan, dan Penelitian',
            'Direktorat Perencanaan dan Pengembangan Strategi Layanan',
            'Direktorat Keuangan dan BMN',
            'Direktorat Layanan Operasional',
            'Non Direktorat / Fungsional',
        ];

        $directorateUnits = Demographic::DIRECTORATE_UNITS;

        $statuses = [
            'PNS',
            'PPPK',
            'BLU (Non PNS Tetap, Non PNS Kontrak, Mitra)',
        ];

        $tenures = [
            '< 1 tahun',
            '1–3 tahun',
            '4–5 tahun',
            '6–10 tahun',
            '> 10 tahun',
        ];

        $ages = [
            '< 25 tahun',
            '25–35 tahun',
            '36–45 tahun',
            '46–55 tahun',
            '> 55 tahun',
        ];

        $genders = [
            'Laki-laki',
            'Perempuan',
        ];

        $educations = [
            'SMA / SMK / Sederajat',
            'Diploma (D3 / D4)',
            'Sarjana (S1)',
            'Profesi (Dokter / Ners / Apoteker / dll.)',
            'Magister (S2) / Spesialis',
            'Doktor (S3) / Subspesialis',
        ];

        $incomes = [
            '< Rp 3.000.000',
            'Rp 3.000.000 – Rp 5.000.000',
            'Rp 5.000.001 – Rp 10.000.000',
            'Rp 10.000.001 – Rp 15.000.000',
            '> Rp 15.000.000',
        ];

        // Nonaktifkan semua opsi demografi lama agar sinkronisasi bersih
        Demographic::query()->update(['is_active' => false]);
        Demographic::where('type', 'children')->delete();

        $hasParentColumn = Schema::hasColumn('demographics', 'parent_name');

        // 1. Sinkronisasi Profesi
        $order = 1;
        foreach ($professions as $name) {
            Demographic::updateOrCreate(
                ['type' => 'profession', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 2. Sinkronisasi Direktorat
        $order = 1;
        foreach ($directorates as $name) {
            Demographic::updateOrCreate(
                ['type' => 'directorate', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 3. Sinkronisasi Satuan Kerja (Unit) di bawah masing-masing Direktorat
        $order = 1;
        foreach ($directorateUnits as $dirName => $units) {
            foreach ($units as $unitName) {
                $payload = ['order' => $order++, 'is_active' => true];
                if ($hasParentColumn) {
                    $payload['parent_name'] = $dirName;
                }

                Demographic::updateOrCreate(
                    ['type' => 'unit', 'name' => $unitName],
                    $payload
                );
            }
        }

        // 4. Sinkronisasi Status Kepegawaian
        $order = 1;
        foreach ($statuses as $name) {
            Demographic::updateOrCreate(
                ['type' => 'status', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 5. Sinkronisasi Masa Kerja
        $order = 1;
        foreach ($tenures as $name) {
            Demographic::updateOrCreate(
                ['type' => 'tenure', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 6. Sinkronisasi Rentang Usia
        $order = 1;
        foreach ($ages as $name) {
            Demographic::updateOrCreate(
                ['type' => 'age', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 7. Sinkronisasi Jenis Kelamin
        $order = 1;
        foreach ($genders as $name) {
            Demographic::updateOrCreate(
                ['type' => 'gender', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 8. Sinkronisasi Latar Belakang Pendidikan
        $order = 1;
        foreach ($educations as $name) {
            Demographic::updateOrCreate(
                ['type' => 'education', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        // 9. Sinkronisasi Jumlah Pendapatan
        $order = 1;
        foreach ($incomes as $name) {
            Demographic::updateOrCreate(
                ['type' => 'income', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }
    }
}

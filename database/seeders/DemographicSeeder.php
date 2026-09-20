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
            'Dokter/Dokter Gigi',
            'Perawat',
            'Bidan',
            'Tenaga Kesehatan Lainnya',
            'Administrasi',
            'Penunjang',
            'Manajemen',
            'Lainnya',
        ];

        $units = [
            'IGD',
            'Rawat Inap',
            'Rawat Jalan',
            'ICU',
            'Kamar Operasi',
            'Farmasi',
            'Laboratorium',
            'Radiologi',
            'Rekam Medis',
            'Administrasi',
            'Keuangan',
            'SDM',
            'Penunjang',
            'Lainnya',
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

        $order = 1;
        foreach ($professions as $name) {
            Demographic::updateOrCreate(
                ['type' => 'profession', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        $order = 1;
        foreach ($units as $name) {
            Demographic::updateOrCreate(
                ['type' => 'unit', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        $order = 1;
        foreach ($statuses as $name) {
            Demographic::updateOrCreate(
                ['type' => 'status', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }

        $order = 1;
        foreach ($tenures as $name) {
            Demographic::updateOrCreate(
                ['type' => 'tenure', 'name' => $name],
                ['order' => $order++, 'is_active' => true]
            );
        }
    }
}

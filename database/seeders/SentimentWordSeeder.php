<?php

namespace Database\Seeders;

use App\Models\SentimentWord;
use Illuminate\Database\Seeder;

class SentimentWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $words = [
            // Positive
            ['word' => 'solid', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Kekompakan tim & rekan kerja'],
            ['word' => 'apresiasi', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Pengakuan dari atasan atau manajemen'],
            ['word' => 'kolaboratif', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Kerjasama lintas unit RS'],
            ['word' => 'ramah', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Sikap rekan dan pasien'],
            ['word' => 'harmonis', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Hubungan kerja positif'],
            ['word' => 'memuaskan', 'sentiment' => 'positive', 'weight' => 3, 'notes' => 'Kepuasan kerja'],
            ['word' => 'kondusif', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Lingkungan kerja nyaman'],
            ['word' => 'profesional', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Sikap kerja tim medis'],
            ['word' => 'kekeluargaan', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Suasana hangat antar karyawan'],
            ['word' => 'didukung', 'sentiment' => 'positive', 'weight' => 2, 'notes' => 'Dukungan fasilitas atau manajemen'],

            // Neutral / Hospital SOP / Clinical units
            ['word' => 'rme', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Rekam Medis Elektronik'],
            ['word' => 'cssd', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Central Sterile Supply Department'],
            ['word' => 'k3rs', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Kesehatan dan Keselamatan Kerja Rumah Sakit'],
            ['word' => 'shift', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Jadwal giliran dinas perawat/staf'],
            ['word' => 'sop', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Standar Operasional Prosedur'],
            ['word' => 'simrs', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Sistem Informasi Manajemen RS'],
            ['word' => 'apd', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Alat Pelindung Diri'],
            ['word' => 'igd', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Instalasi Gawat Darurat'],
            ['word' => 'rawat inap', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Unit perawatan pasien'],
            ['word' => 'farmasi', 'sentiment' => 'neutral', 'weight' => 1, 'notes' => 'Depo obat dan instalasi farmasi'],

            // Negative
            ['word' => 'lembur', 'sentiment' => 'negative', 'weight' => 2, 'notes' => 'Beban jam kerja berlebih'],
            ['word' => 'kelelahan', 'sentiment' => 'negative', 'weight' => 3, 'notes' => 'Burnout fisik / mental staf'],
            ['word' => 'beban', 'sentiment' => 'negative', 'weight' => 2, 'notes' => 'Beban tugas tidak proporsional'],
            ['word' => 'lambat', 'sentiment' => 'negative', 'weight' => 2, 'notes' => 'Sistem atau koordinasi lambat'],
            ['word' => 'tertunda', 'sentiment' => 'negative', 'weight' => 2, 'notes' => 'Insentif atau logistik tertunda'],
            ['word' => 'penat', 'sentiment' => 'negative', 'weight' => 2, 'notes' => 'Keletihan shift panjang'],
            ['word' => 'stres', 'sentiment' => 'negative', 'weight' => 3, 'notes' => 'Tekanan kerja tinggi'],
            ['word' => 'kurang', 'sentiment' => 'negative', 'weight' => 1, 'notes' => 'Kekurangan staf atau alat'],
            ['word' => 'rusak', 'sentiment' => 'negative', 'weight' => 2, 'notes' => 'Alat medis / sarana rusak'],
            ['word' => 'kecewa', 'sentiment' => 'negative', 'weight' => 3, 'notes' => 'Kekecewaan pada kebijakan'],
        ];

        foreach ($words as $data) {
            SentimentWord::updateOrCreate(
                ['word' => $data['word']],
                [
                    'sentiment' => $data['sentiment'],
                    'is_active' => true,
                    'notes' => $data['notes'],
                ]
            );
        }
    }
}

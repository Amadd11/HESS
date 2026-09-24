<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryMap = Category::pluck('id', 'code');

        // 24 Butir Soal HESS RSUP Dr. Sardjito (8 Unsur x 3 Soal)
        $hospitalFactors = [
            // Lingkungan Kerja (LK)
            ['LK', 'H1', 'Lingkungan kerja RS Dr. Sardjito mendukung dan memudahkan Saya untuk bekerja sama dalam satu tim (teamwork) di satuan kerja.'],
            ['LK', 'H2', 'Lingkungan kerja RS Dr. Sardjito mendukung dan memudahkan Saya untuk bekerja sama lintas satuan kerja.'],
            ['LK', 'H3', 'Saya merasa sarana prasarana di RS Dr. Sardjito memadai untuk saya bekerja.'],

            // Hubungan dengan Atasan (HA)
            ['HA', 'H4', 'Menurut Saya, hubungan antara atasan langsung dan pegawai di RS Dr. Sardjito telah berlangsung dengan baik.'],
            ['HA', 'H5', 'Menurut saya, pengakuan Direksi Teknis terhadap kinerja pegawai (dalam bentuk feedback, penghargaan) telah diterapkan dengan baik.'],
            ['HA', 'H6', 'Menurut saya, pengakuan Direksi terhadap kinerja pegawai (dalam bentuk feedback, penghargaan) telah diterapkan dengan baik.'],

            // Penghargaan dan Pengukuran Kerja (PP)
            ['PP', 'H7', 'Menurut saya, sistem reward (penghargaan) di RS Dr. Sardjito bagi pegawai berprestasi dan berinovasi telah berjalan dengan baik.'],
            ['PP', 'H8', 'Menurut saya, sistem sanksi (konsekuensi) di RS Dr. Sardjito yang melanggar disiplin pegawai telah berjalan dengan baik.'],
            ['PP', 'H9', 'Proses penilaian kinerja pegawai sudah dilaksanakan sesuai dengan kinerja.'],

            // Kesempatan Pengembangan Karir (KPK)
            ['KPK', 'H10', 'RS Dr. Sardjito berkomitmen untuk pengembangan kompetensi sesuai dengan kebutuhan peta karir.'],
            ['KPK', 'H11', 'RS Dr. Sardjito memberi kesempatan saya untuk mengembangkan karir melalui pendidikan formal (contoh: sekolah lanjutan) RS Dr. Sardjito.'],
            ['KPK', 'H12', 'RS Dr. Sardjito memberi kesempatan saya untuk mengembangkan karir melalui pendidikan non formal baik di dalam maupun di luar RS Dr. Sardjito.'],

            // Gaji dan Kompensasi (GK)
            ['GK', 'H13', 'Saya memahami komponen apa saja yang dijadikan indikator dalam perhitungan gaji dan remunerasi di RS Dr. Sardjito.'],
            ['GK', 'H14', 'Menurut Saya, gaji dan remunerasi di RS Dr. Sardjito memiliki nilai komparasi yang lebih kompetitif terhadap rumah sakit lainnya.'],
            ['GK', 'H15', 'RS Dr. Sardjito memberikan kepastian gaji dan remunerasi tepat waktu.'],

            // Keseimbangan Kerja dan Kehidupan / Work Life Balance (WLB)
            ['WLB', 'H16', 'Pekerjaan saya memungkinkan saya untuk tetap menjaga kesehatan fisik dan mental.'],
            ['WLB', 'H17', 'Saya merasa dapat mengelola waktu secara fleksibel antara pekerjaan dan kehidupan pribadi saya dengan baik di RS Dr. Sardjito.'],
            ['WLB', 'H18', 'Saya mendapatkan kemudahan dalam memperoleh hak-hak kepegawaian (cuti, kebugaran, kegiatan spiritual).'],

            // Komunikasi dalam Rumah Sakit (KRS)
            ['KRS', 'H19', 'Saya merasa mendapatkan informasi yang dibutuhkan untuk menjalankan pekerjaan dengan baik.'],
            ['KRS', 'H20', 'Menurut saya, komunikasi antar pegawai di RS Dr. Sardjito sudah berjalan dengan lancar.'],
            ['KRS', 'H21', 'Terdapat fasilitas saluran komunikasi melalui hotline kepegawaian, e-prens, saluran komplain, FGD, EFS, sambung rasa untuk menyampaikan ide, gagasan, permasalahan.'],

            // Budaya Rumah Sakit (BRS)
            ['BRS', 'H22', 'Saya mampu menerapkan budaya kerja BerAKHLAK dan budaya 5R (Ringkas, Rapi, Resik, Rawat, Rajin).'],
            ['BRS', 'H23', 'Saya merasa bahwa budaya kerja di RS Dr. Sardjito mempengaruhi kinerja dan produktivitas saya.'],
            ['BRS', 'H24', 'Saya memahami bahwa RS Dr. Sardjito merupakan RS Pendidikan yang mengedepankan Pendidikan Bermartabat.'],
        ];

        $activeCodes = array_column($hospitalFactors, 1);
        Question::whereNotIn('code', $activeCodes)->update(['is_active' => false]);

        $order = 1;
        foreach ($hospitalFactors as $factor) {
            Question::updateOrCreate(
                ['code' => $factor[1]],
                [
                    'category_id' => $categoryMap[$factor[0]],
                    'code' => $factor[1],
                    'text' => $factor[2],
                    'scale' => 'agreement',
                    'subscale' => null,
                    'order' => $order++,
                    'is_active' => true,
                ]
            );
        }
    }
}

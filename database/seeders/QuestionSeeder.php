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

        // Subskala baku MSQ-20
        $intrinsicIndices = [1, 2, 3, 4, 7, 8, 9, 10, 11, 15, 16, 20];
        $extrinsicIndices = [5, 6, 12, 13, 14, 19];

        // 20 Butir Soal MSQ-20
        $msqQuestions = [
            'Kesempatan untuk tetap aktif selama jam kerja',
            'Kesempatan untuk bekerja secara mandiri',
            'Kesempatan melakukan berbagai jenis tugas dalam pekerjaan',
            'Kesempatan memperoleh kemajuan atau status melalui pekerjaan',
            'Cara atasan memperlakukan dan membimbing saya',
            'Kompetensi atasan dalam mengambil keputusan',
            'Kesempatan melakukan pekerjaan yang sesuai dengan nilai dan prinsip saya',
            'Jaminan dan keamanan pekerjaan saya',
            'Kesempatan membantu dan melayani orang lain melalui pekerjaan',
            'Kesempatan memimpin atau mengarahkan orang lain',
            'Kesempatan menggunakan kemampuan terbaik yang saya miliki',
            'Pelaksanaan kebijakan dan aturan rumah sakit',
            'Gaji atau kompensasi yang saya terima dibandingkan dengan pekerjaan saya',
            'Kesempatan untuk memperoleh promosi atau kemajuan',
            'Kebebasan menggunakan pertimbangan sendiri dalam bekerja',
            'Kesempatan menggunakan kreativitas dalam bekerja',
            'Kondisi lingkungan tempat saya bekerja',
            'Hubungan saya dengan rekan kerja',
            'Pengakuan atau penghargaan atas pekerjaan yang saya lakukan',
            'Rasa pencapaian yang saya peroleh dari pekerjaan',
        ];

        $order = 1;
        foreach ($msqQuestions as $i => $text) {
            $num = $i + 1;
            $subscale = in_array($num, $intrinsicIndices, true)
                ? 'intrinsic'
                : (in_array($num, $extrinsicIndices, true) ? 'extrinsic' : 'general');

            Question::updateOrCreate(
                ['code' => "MSQ{$num}"],
                [
                    'category_id' => $categoryMap['MSQ'],
                    'code' => "MSQ{$num}",
                    'text' => $text,
                    'scale' => 'satisfaction',
                    'subscale' => $subscale,
                    'order' => $order++,
                    'is_active' => true,
                ]
            );
        }

        // 24 Butir Soal Hospital Work Factors (8 Kategori x 3 Soal)
        $hospitalFactors = [
            // Leadership & Supervision (LS)
            ['LS', 'H1', 'Atasan memberikan arahan kerja yang jelas.'],
            ['LS', 'H2', 'Atasan memberikan dukungan ketika saya menghadapi masalah pekerjaan.'],
            ['LS', 'H3', 'Atasan memperlakukan pegawai secara adil dan terbuka terhadap masukan.'],

            // Workload & Staffing (WS)
            ['WS', 'H4', 'Beban kerja saya sesuai dengan kapasitas dan waktu kerja yang tersedia.'],
            ['WS', 'H5', 'Jumlah tenaga di unit saya memadai untuk memberikan pelayanan yang optimal.'],
            ['WS', 'H6', 'Pembagian tugas dan jadwal kerja di unit saya dilakukan secara wajar.'],

            // Compensation & Reward (CR)
            ['CR', 'H7', 'Sistem remunerasi atau insentif di rumah sakit diterapkan secara adil.'],
            ['CR', 'H8', 'Saya memahami dasar pemberian remunerasi atau insentif yang saya terima.'],
            ['CR', 'H9', 'Kontribusi dan kinerja pegawai mendapatkan penghargaan yang memadai.'],

            // Career & Professional Development (CD)
            ['CD', 'H10', 'Rumah sakit memberikan kesempatan yang cukup untuk meningkatkan kompetensi saya.'],
            ['CD', 'H11', 'Saya memiliki kesempatan untuk mengembangkan karier di rumah sakit.'],
            ['CD', 'H12', 'Kesempatan mengikuti pelatihan atau pengembangan diberikan secara adil.'],

            // Work Environment & Facilities (EF)
            ['EF', 'H13', 'Lingkungan kerja saya nyaman dan mendukung pelaksanaan pekerjaan.'],
            ['EF', 'H14', 'Fasilitas dan peralatan kerja yang saya perlukan tersedia secara memadai.'],
            ['EF', 'H15', 'Rumah sakit menyediakan lingkungan kerja yang aman bagi pegawai.'],

            // Teamwork & Interprofessional Collaboration (TC)
            ['TC', 'H16', 'Rekan kerja saling membantu dalam melaksanakan pekerjaan.'],
            ['TC', 'H17', 'Komunikasi antarprofesi berjalan dengan baik.'],
            ['TC', 'H18', 'Kolaborasi antarunit dalam memberikan pelayanan berjalan efektif.'],

            // Psychological & Patient Safety (PS)
            ['PS', 'H19', 'Saya merasa aman menyampaikan masalah yang dapat memengaruhi keselamatan pasien.'],
            ['PS', 'H20', 'Saya merasa aman melaporkan kesalahan atau risiko keselamatan tanpa takut mendapatkan perlakuan yang tidak adil.'],
            ['PS', 'H21', 'Rumah sakit mendorong perbaikan ketika terjadi masalah, bukan hanya menyalahkan individu.'],

            // Work-Life Balance (WB)
            ['WB', 'H22', 'Jadwal kerja memungkinkan saya menjaga keseimbangan kehidupan kerja dan kehidupan pribadi.'],
            ['WB', 'H23', 'Saya mendapatkan waktu istirahat yang memadai selama bekerja.'],
            ['WB', 'H24', 'Saya dapat menggunakan hak cuti atau waktu istirahat sesuai ketentuan.'],
        ];

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

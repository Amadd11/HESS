<?php

namespace Database\Seeders;

use App\Models\Demographic;
use App\Models\Period;
use App\Models\Question;
use App\Services\SurveyResponseService;
use Illuminate\Database\Seeder;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(SurveyResponseService $responseService): void
    {
        $period = Period::active()->first();

        if (! $period) {
            $this->command->warn('Tidak ada periode aktif. Jalankan PeriodSeeder terlebih dahulu.');
            return;
        }

        $questions = Question::active()->get();
        $demographics = Demographic::getGroupedOptions();
        $professions = $demographics['professions'];
        $units = $demographics['units'];
        $statuses = $demographics['statuses'];
        $tenures = $demographics['tenures'];

        $likesPool = [
            'Kekompakan dan rasa kekeluargaan antar rekan kerja sangat solid.',
            'Fasilitas dan alat medis di unit kami cukup lengkap dan modern.',
            'Kepemimpinan kepala ruangan yang adil dan terbuka terhadap saran.',
            'Kesempatan mengikuti pelatihan dan seminar ilmiah didukung oleh RS.',
            'Lingkungan kerja yang bersih, nyaman, dan mendukung keselamatan pasien.',
            'Kerja sama interprofesi antara dokter, perawat, dan farmasi berjalan sangat baik.',
            'Gaji dan insentif selalu dibayarkan tepat waktu.',
            'Rasa bangga bisa membantu dan melayani pasien dengan maksimal.',
            'Adanya program reward tahunan untuk pegawai berprestasi.',
            'Jadwal dinas yang teratur dan toleran terhadap kebutuhan keluarga.',
        ];

        $improvesPool = [
            'Jumlah tenaga perawat jaga malam di ruang rawat perlu ditambah agar beban kerja seimbang.',
            'Transparansi pembagian jasa pelayanan atau insentif kinerja perlu ditingkatkan.',
            'Fasilitas ruang istirahat pegawai dan loker ganti pakaian perlu diperluas.',
            'Waktu tunggu disposisi resep di farmasi pada jam sibuk perlu dioptimalkan.',
            'Peralatan komputer dan jaringan rekam medis elektronik (RME) terkadang lambat.',
            'Perluasan kesempatan kenaikan status bagi pegawai kontrak yang sudah lama mengabdi.',
            'Penyediaan konsumsi atau nutrisi tambahan bagi petugas yang dinas malam.',
            'Komunikasi koordinasi antar unit penunjang dan bangsal rawat inap perlu dipercepat.',
            'Fasilitas parkir khusus untuk pegawai shift malam perlu diprioritaskan keamanannya.',
            'Pelatihan penanganan komplain pasien secara berkala untuk staf frontliner.',
        ];

        $totalResponden = 60;

        for ($i = 1; $i <= $totalResponden; $i++) {
            $profile = [
                'profession' => $professions[array_rand($professions)],
                'unit' => $units[array_rand($units)],
                'status' => $statuses[array_rand($statuses)],
                'tenure' => $tenures[array_rand($tenures)],
            ];

            $tendency = fake()->randomElement(['high', 'high', 'high', 'moderate', 'moderate', 'low']);

            $answers = [];
            foreach ($questions as $q) {
                $score = match ($tendency) {
                    'high' => fake()->randomElement([4, 4, 5, 5, 5, 3]),
                    'moderate' => fake()->randomElement([3, 3, 4, 4, 3, 2]),
                    'low' => fake()->randomElement([1, 2, 2, 3, 2]),
                };
                $answers[$q->id] = $score;
            }

            $overallScore = match ($tendency) {
                'high' => fake()->randomElement([4, 5, 5]),
                'moderate' => fake()->randomElement([3, 4]),
                'low' => fake()->randomElement([1, 2, 3]),
            };

            $npsScore = match ($tendency) {
                'high' => fake()->randomElement([9, 10, 10, 8]),
                'moderate' => fake()->randomElement([7, 8, 8, 7]),
                'low' => fake()->randomElement([2, 3, 4, 5, 6]),
            };

            $overall = [
                'overall_score' => $overallScore,
                'nps_score' => $npsScore,
                'like_text' => fake()->boolean(85) ? $likesPool[array_rand($likesPool)] : null,
                'improve_text' => fake()->boolean(80) ? $improvesPool[array_rand($improvesPool)] : null,
            ];

            $responseService->saveResponse($period, $profile, $overall, $answers);
        }

        $this->command->info("Berhasil men-generate {$totalResponden} data responden survei realistis.");
    }
}

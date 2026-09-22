<?php

namespace Database\Seeders;

use App\Models\Demographic;
use App\Models\Period;
use App\Models\Question;
use App\Services\SurveyResponseService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

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

        $questions = Question::with('category')->active()->get();
        $demographics = Demographic::getGroupedOptions();
        $professions = $demographics['professions'];
        $units = $demographics['units'];
        $statuses = $demographics['statuses'];
        $tenures = $demographics['tenures'];

        // 35+ Kalimat Pujian / Hal Positif Realistis Khas Rumah Sakit
        $likesPool = [
            'Kekompakan dan rasa kekeluargaan antar rekan kerja di unit sangat solid.',
            'Fasilitas dan alat medis di ruangan kami cukup lengkap, canggih, dan terawat.',
            'Kepemimpinan kepala ruangan dan supervisor sangat adil serta terbuka terhadap masukan.',
            'Kesempatan mengikuti seminar ilmiah, workshop akreditasi, dan pelatihan ACLS sangat didukung.',
            'Lingkungan kerja bersih, higienis, dan budaya keselamatan pasien (patient safety) sangat terjaga.',
            'Kolaborasi interprofesi antara dokter penanggung jawab, perawat bangsal, dan farmasi klinis berjalan baik.',
            'Gaji, tunjangan pokok, dan insentif bulanan selalu dibayarkan tepat waktu tanpa kendala.',
            'Rasa bangga yang tinggi bisa melayani dan merawat kesembuhan pasien secara optimal.',
            'Adanya program apresiasi dan penghargaan untuk pegawai serta unit teladan tiap semester.',
            'Jadwal dinas yang disusun dengan bijak dan toleran terhadap kebutuhan mendesak keluarga.',
            'Budaya saling bantu dan sigap saling mem-backup saat ada pasien gawat darurat atau lonjakan IGD.',
            'Proses orientasi bagi staf baru sangat terstruktur dan didampingi perawat senior yang ramah.',
            'Ruang ganti dan seragam kerja medis disediakan dengan standar kebersihan yang sangat baik.',
            'Komunikasi antar departemen penunjang seperti radiologi dan laboratorium sangat responsif.',
            'Kenyamanan musala dan fasilitas ibadah di setiap gedung rumah sakit sangat memadai.',
            'Sistem reward bagi perawat yang berdedikasi tinggi memberikan motivasi kerja yang nyata.',
            'Keamanan lingkungan rumah sakit terjamin dengan satpam yang sigap dan sopan.',
            'Manajemen rumah sakit transparan dalam menyampaikan informasi perkembangan dan target RS.',
            'Sistem elektronik RME memudahkan pencatatan rekam medis pasien secara cepat dan rapi.',
            'Dokter spesialis sangat menghargai saran observasi klinis dari rekan perawat jaga.',
            'Suasana kerja yang harmonis membuat tidak merasa tertekan meskipun pasien sedang ramai.',
            'Pemberian nutrisi dan ekstra puding saat dinas malam sangat membantu stamina kerja.',
            'Ketersediaan APD dan masker medis selalu mencukupi di depo ruangan.',
            'Kenyamanan bekerja di RS ini membuat saya bangga dan berniat mengabdi dalam jangka panjang.',
            'Dukungan moral dari manajemen saat ada insiden keselamatan kerja sangat menenangkan staf.',
            'Sistem presensi digital sangat praktis dan memudahkan pemantauan jam kerja.',
            'Kompensasi lembur dihitung secara adil dan transparan oleh bagian SDM.',
            'Hubungan dengan pasien dan keluarga pasien terjalin hangat dan saling percaya.',
            'Program senam kebugaran dan pemeriksaan kesehatan tahunan untuk karyawan sangat bermanfaat.',
            'Keramahan staf kasir dan administrasi pendaftaran menciptakan citra rumah sakit yang ramah.',
            'Fasilitas penunjang seperti kantin bersih dan harga makanan terjangkau untuk karyawan.',
            'Apresiasi dari keluarga pasien atas pelayanan prima kami menjadi kebahagiaan tersendiri.',
            'Pengelolaan limbah medis dan infeksius dilakukan secara sangat higienis dan profesional.',
            'Semangat gotong royong dan tidak saling menyalahkan saat menghadapi kendala teknis.',
            'Kultur saling menyapa dan rasa hormat antar senior dan junior terjaga dengan baik.',
        ];

        // 40+ Masukan Konstruktif & Area Perbaikan Realistis Khas Rumah Sakit
        $improvesPool = [
            'Jumlah tenaga perawat jaga malam di bangsal rawat inap perlu ditambah agar rasio pasien seimbang.',
            'Transparansi formula pembagian jasa pelayanan (jaspel) dan remunerasi kinerja perlu dijelaskan berkala.',
            'Fasilitas ruang istirahat petugas dan loker penyimpanan pakaian di ruang tindakan perlu diperluas.',
            'Waktu tunggu disposisi obat di depo farmasi rawat jalan saat jam sibuk perlu dioptimalkan.',
            'Jaringan internet wifi dan perangkat komputer sistem rekam medis elektronik (RME) terkadang lambat.',
            'Perlu kejelasan jenjang karir klinis dan percepatan kenaikan status bagi staf kontrak yang lama mengabdi.',
            'Penyediaan konsumsi atau nutrisi tambahan bagi petugas yang bertugas jaga malam perlu ditingkatkan mutunya.',
            'Komunikasi koordinasi antara petugas IGD dan bangsal rawat inap saat transfer pasien perlu dipercepat.',
            'Area parkir khusus sepeda motor karyawan shift malam perlu ditambah penerangan dan pengawasan CCTV.',
            'Pelatihan penanganan komplain pasien dan keluarga secara berkala untuk seluruh staf frontliner.',
            'Pendingin ruangan (AC) di ruang jaga perawat dan stasi rawat inap barat sering kurang dingin.',
            'Ketersediaan alat tensimeter digital dan termometer di bangsal perlu ditambah cadangannya.',
            'Rotasi dinas shift malam ke pagi (long shift) sebaiknya diberikan jeda istirahat minimal 24 jam.',
            'Prosedur permintaan logistik barang habis pakai medis antar ruangan sebaiknya disederhanakan.',
            'Ruang ganti staf wanita perlu ditambah cermin dan fasilitas toilet yang lebih bersih.',
            'SOP penanganan pasien agresif atau keluarga yang emosional perlu disosialisasikan lebih jelas.',
            'Distribusi beban kerja saat akhir pekan (weekend) perlu diawasi agar tidak menumpuk di staf junior.',
            'Pemberian kesempatan cuti tahunan sebaiknya lebih fleksibel dan tidak dipersulit saat bukan musim puncak.',
            'Stok spuit dan selang infus di depo obat terkadang terlambat datang dari gudang pusat farmasi.',
            'Komunikasi kepala bidang ke staf pelaksana di lantai bawah perlu lebih ramah dan mengayomi.',
            'Kompensasi uang jaga dan lembur mohon agar ditransfer bersamaan dengan gaji pokok.',
            'Fasilitas dispenser air minum galon di stasi perawat sering lambat diganti saat habis.',
            'Perlu adanya forum konseling psikologis atau pelepasan stres kerja bagi nakes di unit intensif ICU/IGD.',
            'Sistem antrean pendaftaran online pasien BPJS perlu diperbaiki agar tidak menumpuk di loket pagi.',
            'Kursi kerja di bagian administrasi dan rekam medis sudah banyak yang aus dan perlu diganti ergonomis.',
            'Perlu pengadaan alat pelindung diri (APD) dengan ukuran yang lebih sesuai dan nyaman dipakai lama.',
            'Evaluasi kinerja berkala sebaiknya dibarengi dengan sesi feedback dua arah yang konstruktif.',
            'Kebisingan di lorong kamar rawat inap saat jam istirahat malam mohon dapat lebih ditertibkan.',
            'Sosialisasi kebijakan baru rumah sakit sebaiknya diberikan minimal seminggu sebelum diberlakukan.',
            'Bimbingan teknis penggunaan modul RME baru perlu diulang bagi staf senior yang belum terbiasa.',
            'Tempat sampah medis di koridor luar sebaiknya menggunakan sistem pedal injak tanpa sentuhan tangan.',
            'Dukungan beasiswa atau izin belajar untuk jenjang profesi ners dan spesialis mohon diperbanyak.',
            'Tunjangan risiko kerja bagi staf radiologi dan laboratorium infeksius perlu ditinjau ulang.',
            'Kecepatan lift pengangkut brankar pasien saat jam besuk sering terhambat oleh pengunjung umum.',
            'Pemeriksaan kesehatan berkala (medical check-up) untuk karyawan sebaiknya mencakup skrining lengkap.',
            'Perlunya ruang transit yang layak bagi keluarga pasien yang menunggu tindakan operasi darurat.',
            'Koordinasi jadwal operasi elektif antara dokter operator dan tim anastesi perlu lebih presisi.',
            'Penataan kabel komputer dan peralatan medis di stasi perawat perlu dirapikan demi keamanan.',
            'Seragam dinas baru sebaiknya menggunakan bahan yang lebih menyerap keringat dan adem.',
            'Keseimbangan antara tugas administratif berkas klaim dan waktu pelayanan langsung ke pasien.',
        ];

        $totalResponden = 500;

        for ($i = 1; $i <= $totalResponden; $i++) {
            $profile = [
                'profession' => $professions[array_rand($professions)],
                'unit' => $units[array_rand($units)],
                'status' => $statuses[array_rand($statuses)],
                'tenure' => $tenures[array_rand($tenures)],
            ];

            // 1. Tentukan profil kepuasan umum responden (60% puas/tinggi, 25% moderat, 15% kritis/rendah)
            $persona = fake()->randomElement([
                'promoter_high',
                'promoter_high',
                'promoter_high',
                'satisfied',
                'satisfied',
                'moderate',
                'moderate',
                'critical',
            ]);

            // 2. Acak skor per butir pertanyaan dengan variasi realistis (ada yang 1 s/d 5)
            $answers = [];
            foreach ($questions as $q) {
                $categoryType = $q->category?->type ?? 'msq';
                $subscale = $q->subscale;

                // Tentukan probabilitas skor sesuai profil responden & karakteristik kategori
                $scoreWeights = match ($persona) {
                    'promoter_high' => [5 => 60, 4 => 30, 3 => 8, 2 => 2, 1 => 0],
                    'satisfied' => [5 => 35, 4 => 45, 3 => 15, 2 => 4, 1 => 1],
                    'moderate' => [5 => 15, 4 => 35, 3 => 35, 2 => 12, 1 => 3],
                    'critical' => [5 => 5, 4 => 15, 3 => 30, 2 => 30, 1 => 20],
                };

                // Soal kompensasi/beban kerja secara umum di rumah sakit cenderung dinilai sedikit lebih kritis
                if (in_array($q->category?->code, ['CAT-003', 'CAT-002', 'C-EXT'])) {
                    $scoreWeights[5] = max(0, $scoreWeights[5] - 15);
                    $scoreWeights[3] += 10;
                    $scoreWeights[2] += 5;
                }

                // Bangun array acak berbobot
                $weightedPool = [];
                foreach ($scoreWeights as $sc => $weight) {
                    for ($w = 0; $w < $weight; $w++) {
                        $weightedPool[] = $sc;
                    }
                }

                $answers[$q->id] = ! empty($weightedPool) ? fake()->randomElement($weightedPool) : rand(1, 5);
            }

            // 3. Skor Keseluruhan & eNPS yang berkorelasi natural dengan persona
            $overallScore = match ($persona) {
                'promoter_high' => fake()->randomElement([5, 5, 4]),
                'satisfied' => fake()->randomElement([4, 4, 5, 3]),
                'moderate' => fake()->randomElement([3, 4, 3]),
                'critical' => fake()->randomElement([1, 2, 2, 3]),
            };

            $npsScore = match ($persona) {
                'promoter_high' => fake()->randomElement([9, 10, 10, 10, 9]),
                'satisfied' => fake()->randomElement([8, 9, 8, 9, 7]),
                'moderate' => fake()->randomElement([7, 8, 7, 6, 8]),
                'critical' => fake()->randomElement([2, 3, 4, 5, 1, 6]),
            };

            // 4. Komentar kualitatif bervariasi (70-80% mengisi, 20-30% mengosongkan layaknya survei nyata)
            $overall = [
                'overall_score' => $overallScore,
                'nps_score' => $npsScore,
                'like_text' => fake()->boolean(78) ? $likesPool[array_rand($likesPool)] : null,
                'improve_text' => fake()->boolean(72) ? $improvesPool[array_rand($improvesPool)] : null,
            ];

            // 5. Waktu pengisian acak tersebar selama 30 hari terakhir agar grafik tren timeline hidup
            $randomDaysAgo = rand(0, 30);
            $randomHoursAgo = rand(0, 23);
            $randomMinutesAgo = rand(0, 59);
            $completedAt = Carbon::now()->subDays($randomDaysAgo)->subHours($randomHoursAgo)->subMinutes($randomMinutesAgo);

            $responseService->saveResponse($period, $profile, $overall, $answers, $completedAt);
        }

        $this->command->info("Berhasil men-generate {$totalResponden} data responden survei realistis dan acak alami.");
    }
}

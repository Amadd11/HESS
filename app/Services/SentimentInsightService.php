<?php

namespace App\Services;

class SentimentInsightService
{
    /**
     * Hasilkan insight komprehensif (Topik Positif, Topik Negatif, Rekomendasi, Kesimpulan).
     *
     * @param  array<string, mixed>  $kpis
     * @param  array<string, int>  $topPositive
     * @param  array<string, int>  $topNegative
     * @return array{positive_topics: array<string>, negative_topics: array<string>, recommendations: array<string>, conclusion: string}
     */
    public function generateInsights(array $kpis, array $topPositive, array $topNegative): array
    {
        $posPercent = (int) ($kpis['positive']['percent'] ?? 0);
        $negPercent = (int) ($kpis['negative']['percent'] ?? 0);
        $neuPercent = (int) ($kpis['neutral']['percent'] ?? 0);

        $posWords = array_keys($topPositive);
        $negWords = array_keys($topNegative);

        // 1. Topik Positif Utama
        $positiveTopics = ! empty($posWords)
            ? array_slice($posWords, 0, 4)
            : ['pelayanan pasien', 'kerjasama tim', 'dukungan pimpinan', 'kenyamanan ruang'];

        // 2. Topik Negatif / Isu Kritis
        $negativeTopics = ! empty($negWords)
            ? array_slice($negWords, 0, 4)
            : ['beban kerja', 'fasilitas kurang', 'waktu istirahat', 'koordinasi shift'];

        // 3. Rekomendasi Aksi Manajemen RS
        $recommendations = [];
        if (in_array('beban kerja', $negWords, true) || in_array('lembur', $negWords, true) || in_array('keterbatasan tenaga', $negWords, true)) {
            $recommendations[] = 'Evaluasi beban kerja dan kecukupan rasio tenaga per shift, khususnya pada unit instalasi berisiko tinggi.';
        }
        if (in_array('fasilitas kurang', $negWords, true) || in_array('ruang kerja', $negWords, true) || in_array('parkir', $negWords, true)) {
            $recommendations[] = 'Peremajaan dan pemeliharaan sarana pendukung kerja staf (fasilitas istirahat, parkir shift malam, dan kelengkapan penunjang).';
        }
        if (in_array('komunikasi kurang', $negWords, true) || in_array('tidak jelas', $negWords, true)) {
            $recommendations[] = 'Optimalisasi alur komunikasi koordinasi antar unit medis dan non-medis untuk meminimalkan miskomunikasi pelayanan.';
        }
        if (empty($recommendations)) {
            $recommendations = [
                'Pertahankan budaya kerja kolaboratif dan apresiasi berkala bagi staf yang berprestasi.',
                'Lakukan peninjauan berkala terhadap SOP operasional agar fleksibel mendukung efisiensi pelayanan.',
                'Tingkatkan forum dialog terbuka antara pimpinan instalasi dan staf fungsional.',
            ];
        }

        // 4. Narasi Kesimpulan Eksekutif
        $posThemesStr = implode(', ', array_slice($positiveTopics, 0, 3));
        $negThemesStr = implode(', ', array_slice($negativeTopics, 0, 3));

        if ($posPercent >= 50) {
            $conclusion = "Tanggapan pegawai menunjukkan tingkat kepuasan yang solid dengan sentimen positif mendominasi ({$posPercent}%), didorong oleh faktor {$posThemesStr}. Meskipun demikian, manajemen perlu memberi perhatian pada area perbaikan ({$negPercent}%) yang terfokus pada {$negThemesStr} guna menjaga retensi pegawai.";
        } elseif ($posPercent >= 35) {
            $conclusion = "Sentimen positif pegawai berada pada tingkat cukup baik ({$posPercent}%), dengan keunggulan pada {$posThemesStr}. Namun, catatan evaluasi sebesar {$negPercent}% mengenai {$negThemesStr} mengindikasikan perlunya pembenahan sistemik agar tidak menurunkan moral kerja.";
        } else {
            $conclusion = "Kondisi masukan pegawai menuntut perhatian intensif, dengan porsi evaluasi netral ({$neuPercent}%) dan negatif ({$negPercent}%) yang signifikan. Prioritas utama perbaikan tertuju pada penanganan isu {$negThemesStr}, sembari mempertahankan nilai positif pada {$posThemesStr}.";
        }

        return [
            'positive_topics' => $positiveTopics,
            'negative_topics' => $negativeTopics,
            'recommendations' => $recommendations,
            'conclusion' => $conclusion,
        ];
    }
}

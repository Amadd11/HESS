<?php

namespace App\Services;

class WordCloudService
{
    /**
     * Stopwords Bahasa Indonesia umum & kata umum konteks RS untuk eliminasi noise.
     *
     * @var array<string, bool>
     */
    protected array $stopWords = [
        'yang' => true, 'di' => true, 'ke' => true, 'dari' => true, 'ini' => true,
        'itu' => true, 'untuk' => true, 'pada' => true, 'adalah' => true, 'dan' => true,
        'atau' => true, 'dengan' => true, 'oleh' => true, 'karena' => true, 'juga' => true,
        'saya' => true, 'kami' => true, 'kita' => true, 'mereka' => true, 'dia' => true,
        'bisa' => true, 'dapat' => true, 'akan' => true, 'telah' => true, 'sudah' => true,
        'harus' => true, 'perlu' => true, 'ada' => true, 'jadi' => true, 'dalam' => true,
        'secara' => true, 'agar' => true, 'supaya' => true, 'tentang' => true, 'sebagai' => true,
        'lebih' => true, 'sangat' => true, 'selalu' => true, 'banyak' => true, 'hanya' => true,
        'bila' => true, 'jika' => true, 'ketika' => true, 'saat' => true, 'karyawan' => true,
        'pegawai' => true, 'staf' => true, 'rumah' => true, 'sakit' => true, 'rs' => true,
        'hal' => true, 'saja' => true, 'masih' => true, 'bagi' => true, 'antara' => true,
        'mohon' => true, 'tolong' => true, 'harap' => true, 'semoga' => true, 'tetap' => true,
        'yg' => true, 'nya' => true, 'dgn' => true, 'utk' => true, 'dg' => true,
        'saling' => true, 'sebaiknya' => true, 'terima' => true, 'kasih' => true, 'terimakasih' => true,
        'maupun' => true, 'namun' => true, 'tetapi' => true, 'tapi' => true, 'serta' => true, 'lain' => true, 'lainnya' => true,
    ];

    /**
     * Bangun item Word Cloud untuk masing-masing tab sentimen (positive, neutral, negative).
     *
     * @param  array<string, int>  $freqList
     * @return array<int, array{word: string, count: int, scale: int, sentiment: string}>
     */
    public function buildCloud(array $freqList, string $sentimentType, int $maxItems = 35): array
    {
        // Filter stopwords jika masih lolos
        $filtered = [];
        foreach ($freqList as $word => $count) {
            $w = trim((string) $word);
            if ($w !== '' && ! isset($this->stopWords[$w]) && mb_strlen($w) >= 3) {
                $filtered[$w] = $count;
            }
        }

        $slice = array_slice($filtered, 0, $maxItems, true);

        // Fallback representatif jika data survei baru dimulai
        if (empty($slice)) {
            $slice = match ($sentimentType) {
                'positive' => [
                    'pelayanan' => 14, 'teamwork' => 11, 'dukungan' => 9, 'profesional' => 8,
                    'nyaman' => 7, 'semangat' => 7, 'apresiasi' => 6, 'kesempatan' => 5,
                    'kolaborasi' => 5, 'termotivasi' => 4, 'membantu' => 4, 'komunikasi baik' => 4,
                ],
                'neutral' => [
                    'prosedur' => 12, 'sistem' => 10, 'informasi' => 9, 'laporan' => 8,
                    'aturan' => 7, 'standar' => 6, 'kebijakan' => 6, 'tugas' => 5,
                    'administrasi' => 5, 'jadwal' => 4, 'mekanisme' => 4, 'proses' => 3,
                ],
                'negative' => [
                    'beban kerja' => 15, 'gaji' => 11, 'kurang' => 9, 'stres' => 8,
                    'lembur' => 7, 'fasilitas kurang' => 6, 'keterbatasan tenaga' => 6,
                    'komunikasi kurang' => 5, 'tidak adil' => 5, 'tekanan' => 4,
                ],
                default => [],
            };
        }

        $maxVal = ! empty($slice) ? max($slice) : 1;
        $minVal = ! empty($slice) ? min($slice) : 1;
        $range = max(1, $maxVal - $minVal);

        $items = [];
        foreach ($slice as $word => $count) {
            // Skala ukuran font dari 1 (terkecil) sampai 5 (terbesar)
            $normalized = ($count - $minVal) / $range;
            $scale = 1 + (int) round($normalized * 4);

            $items[] = [
                'word' => $word,
                'count' => $count,
                'scale' => $scale,
                'sentiment' => $sentimentType,
            ];
        }

        shuffle($items);

        return $items;
    }

    /**
     * Cek apakah kata termasuk stopword.
     */
    public function isStopWord(string $word): bool
    {
        return isset($this->stopWords[mb_strtolower(trim($word), 'UTF-8')]);
    }
}

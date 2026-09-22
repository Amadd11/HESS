<?php

namespace App\Services;

use App\Models\Response;
use Illuminate\Support\Collection;

class SentimentAnalysisService
{
    /**
     * Kamus Kata / Frasa Positif (Konteks RS & Kepuasan Kerja).
     *
     * @var array<string, int>
     */
    protected array $positiveWords = [
        'pelayanan' => 2,
        'teamwork' => 2,
        'kerja tim' => 2,
        'kerjasama' => 2,
        'dukungan' => 2,
        'mendukung' => 2,
        'profesional' => 2,
        'nyaman' => 2,
        'kenyamanan' => 2,
        'semangat' => 2,
        'apresiasi' => 2,
        'kesempatan' => 1,
        'kolaborasi' => 2,
        'termotivasi' => 2,
        'motivasi' => 2,
        'membantu' => 2,
        'komunikasi baik' => 2,
        'pengembangan' => 1,
        'lingkungan kerja baik' => 2,
        'ramah' => 2,
        'cepat' => 2,
        'bersih' => 2,
        'puas' => 2,
        'memuaskan' => 2,
        'bagus' => 2,
        'baik' => 1,
        'solid' => 2,
        'kompak' => 2,
        'adil' => 2,
        'aman' => 2,
        'disiplin' => 1,
        'transparan' => 2,
        'tepat waktu' => 2,
        'didukung' => 2,
        'suka' => 1,
        'bangga' => 2,
        'responsif' => 2,
        'peduli' => 2,
        'terbantu' => 2,
        'fasilitas memadai' => 2,
        'lengkap' => 1,
        'teratur' => 1,
        'harmonis' => 2,
        'kompeten' => 2,
        'berkualitas' => 2,
        'terbuka' => 1,
        'menghargai' => 2,
        'terarah' => 1,
        'kondusif' => 2,
        'efisien' => 2,
        'solutif' => 2,
        'penghargaan' => 2,
        'terjamin' => 2,
        'kebersihan' => 2,
        'keamanan' => 2,
        'komunikasi efektif' => 2,
        'pelatihan' => 1,
    ];

    /**
     * Kamus Kata / Frasa Negatif (Konteks Keluhan, Kendala, Perbaikan RS).
     *
     * @var array<string, int>
     */
    protected array $negativeWords = [
        'beban kerja' => 3,
        'beban' => 2,
        'gaji' => 2,
        'kurang' => 2,
        'stres' => 3,
        'lembur' => 2,
        'fasilitas kurang' => 2,
        'keterbatasan tenaga' => 2,
        'kurang tenaga' => 2,
        'komunikasi kurang' => 2,
        'tidak adil' => 3,
        'tekanan' => 2,
        'kejelasan karir' => 2,
        'ketidakpastian' => 2,
        'turnover' => 2,
        'waktu istirahat' => 2,
        'istirahat kurang' => 2,
        'lama' => 2,
        'lambat' => 2,
        'lelet' => 2,
        'antri' => 2,
        'antrean' => 2,
        'menumpuk' => 2,
        'kotor' => 2,
        'bau' => 2,
        'rusak' => 2,
        'panas' => 1,
        'sempit' => 2,
        'pengap' => 2,
        'kasar' => 3,
        'jutek' => 3,
        'kecewa' => 2,
        'kecewa sekali' => 3,
        'buruk' => 2,
        'ribet' => 2,
        'berbelit' => 2,
        'sepihak' => 2,
        'terhambat' => 2,
        'tertunda' => 2,
        'telat' => 2,
        'kelelahan' => 3,
        'capek' => 2,
        'keluhan' => 2,
        'komplain' => 2,
        'tidak jelas' => 2,
        'tidak ramah' => 3,
        'tidak nyaman' => 3,
        'kurang nyaman' => 2,
        'kurang bersih' => 2,
        'kurang cepat' => 2,
        'kurang ramah' => 2,
        'kurang transparan' => 2,
        'tidak transparan' => 3,
        'minim' => 2,
        'sulit' => 2,
        'susah' => 2,
        'bingung' => 1,
        'diskriminatif' => 3,
        'konflik' => 2,
        'masalah' => 1,
        'terabaikan' => 2,
    ];

    /**
     * Kamus Kata Netral / Operasional (Prosedur, Sistem, Administrasi).
     *
     * @var array<string, int>
     */
    protected array $neutralWords = [
        'prosedur' => 2,
        'sistem' => 2,
        'aturan' => 2,
        'standar' => 2,
        'kebijakan' => 2,
        'informasi' => 2,
        'laporan' => 2,
        'administrasi' => 2,
        'jadwal' => 2,
        'tugas' => 2,
        'mekanisme' => 2,
        'proses' => 2,
        'rotasi' => 2,
        'manajemen' => 2,
        'ketersediaan' => 1,
        'ruang kerja' => 1,
        'sosialisasi' => 1,
        'koordinasi' => 1,
        'aplikasi' => 1,
        'pengaturan' => 1,
        'struktur' => 1,
        'rencana' => 1,
        'revisi' => 1,
        'evaluasi' => 1,
        'kegiatan' => 1,
        'dokumen' => 1,
        'pertemuan' => 1,
        'rapat' => 1,
        'data' => 1,
        'arsip' => 1,
    ];

    /**
     * Kata-kata Negasi.
     *
     * @var array<string>
     */
    protected array $negationWords = [
        'tidak', 'bukan', 'kurang', 'belum', 'tanpa', 'jangan',
    ];

    /**
     * Stopwords Bahasa Indonesia.
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
    ];

    /**
     * Klasifikasikan satu record Response ke sentimen (positive, neutral, negative) & skor.
     *
     * @return array{sentiment: string, score: float, label: string, positive_tokens: array<string>, negative_tokens: array<string>, neutral_tokens: array<string>, total_words: int}
     */
    public function classifyResponse(Response $response): array
    {
        $likeText = trim((string) ($response->like_text ?? ''));
        $improveText = trim((string) ($response->improve_text ?? ''));

        $likeAnalysis = $this->analyzeText($likeText, isLikelyPositive: true);
        $improveAnalysis = $this->analyzeText($improveText, isLikelyPositive: false);

        $netScore = ($likeAnalysis['score'] * 1.2) + ($improveAnalysis['score']);
        $totalWords = $likeAnalysis['word_count'] + $improveAnalysis['word_count'];

        // Normalisasi skor ke skala -1.0 s/d +1.0
        $normalizedScore = round(max(-1.0, min(1.0, $netScore / 5.0)), 2);

        if ($netScore > 0.5) {
            $sentiment = 'positive';
            $label = 'Positif';
        } elseif ($netScore < -0.5) {
            $sentiment = 'negative';
            $label = 'Negatif';
        } else {
            $sentiment = 'neutral';
            $label = 'Netral';
        }

        return [
            'sentiment' => $sentiment,
            'score' => $normalizedScore,
            'label' => $label,
            'positive_tokens' => array_merge($likeAnalysis['positive_tokens'], $improveAnalysis['positive_tokens']),
            'negative_tokens' => array_merge($likeAnalysis['negative_tokens'], $improveAnalysis['negative_tokens']),
            'neutral_tokens' => array_merge($likeAnalysis['neutral_tokens'], $improveAnalysis['neutral_tokens']),
            'total_words' => $totalWords,
        ];
    }

    /**
     * Analisis sekumpulan respon untuk menghasilkan token agregat dan respon terklasifikasi.
     *
     * @param  Collection<int, Response>  $responses
     * @return array{
     *     classified_responses: Collection<int, array>,
     *     positive_frequencies: array<string, int>,
     *     negative_frequencies: array<string, int>,
     *     neutral_frequencies: array<string, int>,
     *     total_words: int
     * }
     */
    public function analyzeCollection(Collection $responses): array
    {
        $positiveFreq = [];
        $negativeFreq = [];
        $neutralFreq = [];
        $totalWords = 0;

        $classified = $responses->map(function (Response $response) use (&$positiveFreq, &$negativeFreq, &$neutralFreq, &$totalWords) {
            $res = $this->classifyResponse($response);
            $totalWords += $res['total_words'];

            foreach ($res['positive_tokens'] as $tok) {
                $t = trim($tok);
                if ($t !== '' && ! isset($this->stopWords[$t])) {
                    $positiveFreq[$t] = ($positiveFreq[$t] ?? 0) + 1;
                }
            }

            foreach ($res['negative_tokens'] as $tok) {
                $t = trim($tok);
                if ($t !== '' && ! isset($this->stopWords[$t])) {
                    $negativeFreq[$t] = ($negativeFreq[$t] ?? 0) + 1;
                }
            }

            foreach ($res['neutral_tokens'] as $tok) {
                $t = trim($tok);
                if ($t !== '' && ! isset($this->stopWords[$t])) {
                    $neutralFreq[$t] = ($neutralFreq[$t] ?? 0) + 1;
                }
            }

            return array_merge($response->toArray(), [
                'sentiment' => $res['sentiment'],
                'sentiment_score' => $res['score'],
                'sentiment_label' => $res['label'],
                'formatted_date' => $response->completed_at ? $response->completed_at->format('d M Y') : $response->created_at->format('d M Y'),
            ]);
        });

        arsort($positiveFreq);
        arsort($negativeFreq);
        arsort($neutralFreq);

        return [
            'classified_responses' => $classified,
            'positive_frequencies' => $positiveFreq,
            'negative_frequencies' => $negativeFreq,
            'neutral_frequencies' => $neutralFreq,
            'total_words' => $totalWords,
        ];
    }

    /**
     * Analisis teks perorangan.
     */
    protected function analyzeText(string $text, bool $isLikelyPositive): array
    {
        if (trim($text) === '') {
            return [
                'score' => 0,
                'word_count' => 0,
                'positive_tokens' => [],
                'negative_tokens' => [],
                'neutral_tokens' => [],
            ];
        }

        $cleaned = mb_strtolower($text, 'UTF-8');
        $cleaned = (string) preg_replace('/[^\p{L}\p{N}\s\-]/u', ' ', $cleaned);

        $words = preg_split('/\s+/', trim($cleaned), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $wordCount = count($words);

        $positiveTokens = [];
        $negativeTokens = [];
        $neutralTokens = [];

        $score = $isLikelyPositive ? 1.0 : -0.5;
        $skipNext = false;

        for ($i = 0; $i < $wordCount; $i++) {
            if ($skipNext) {
                $skipNext = false;

                continue;
            }

            $w = $words[$i];
            $nextW = $words[$i + 1] ?? '';
            $bigram = $nextW !== '' ? "{$w} {$nextW}" : null;

            // 1. Frasa 2 Kata
            if ($bigram && isset($this->negativeWords[$bigram])) {
                $negativeTokens[] = $bigram;
                $score -= $this->negativeWords[$bigram];
                $skipNext = true;

                continue;
            }

            if ($bigram && isset($this->positiveWords[$bigram])) {
                $positiveTokens[] = $bigram;
                $score += $this->positiveWords[$bigram];
                $skipNext = true;

                continue;
            }

            if ($bigram && isset($this->neutralWords[$bigram])) {
                $neutralTokens[] = $bigram;
                $skipNext = true;

                continue;
            }

            // 2. Negasi
            if (in_array($w, $this->negationWords, true) && $nextW !== '') {
                if (isset($this->positiveWords[$nextW])) {
                    $phrase = "{$w} {$nextW}";
                    $negativeTokens[] = $phrase;
                    $score -= 2;
                    $skipNext = true;

                    continue;
                }
                if (isset($this->negativeWords[$nextW])) {
                    $phrase = "{$w} {$nextW}";
                    $positiveTokens[] = $phrase;
                    $score += 1.5;
                    $skipNext = true;

                    continue;
                }
            }

            // 3. Kata Tunggal
            if (isset($this->positiveWords[$w])) {
                $positiveTokens[] = $w;
                $score += $this->positiveWords[$w];

                continue;
            }

            if (isset($this->negativeWords[$w])) {
                $negativeTokens[] = $w;
                $score -= $this->negativeWords[$w];

                continue;
            }

            if (isset($this->neutralWords[$w])) {
                $neutralTokens[] = $w;

                continue;
            }

            // 4. Kata netral/operasional jika bukan stopwords
            if (! isset($this->stopWords[$w]) && mb_strlen($w) >= 3) {
                $neutralTokens[] = $w;
            }
        }

        return [
            'score' => $score,
            'word_count' => $wordCount,
            'positive_tokens' => $positiveTokens,
            'negative_tokens' => $negativeTokens,
            'neutral_tokens' => $neutralTokens,
        ];
    }
}

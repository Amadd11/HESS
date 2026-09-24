<?php

namespace App\Services;

use App\Models\Response;
use App\Models\SentimentWord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SentimentAnalysisService
{
    /**
     * Daftar Kata / Frasa Positif (Konteks RS & Kepuasan Kerja).
     *
     * @var array<string>
     */
    protected array $positiveWords = [
        'pelayanan',
        'teamwork',
        'kerja tim',
        'kerjasama',
        'dukungan',
        'mendukung',
        'profesional',
        'nyaman',
        'kenyamanan',
        'semangat',
        'apresiasi',
        'kolaborasi',
        'termotivasi',
        'motivasi',
        'membantu',
        'komunikasi baik',
        'pengembangan',
        'lingkungan kerja baik',
        'ramah',
        'cepat',
        'bersih',
        'puas',
        'memuaskan',
        'bagus',
        'baik',
        'solid',
        'kompak',
        'adil',
        'aman',
        'disiplin',
        'transparan',
        'tepat waktu',
        'didukung',
        'suka',
        'bangga',
        'responsif',
        'peduli',
        'terbantu',
        'fasilitas memadai',
        'lengkap',
        'teratur',
        'harmonis',
        'kompeten',
        'berkualitas',
        'terbuka',
        'menghargai',
        'terarah',
        'kondusif',
        'efisien',
        'solutif',
        'penghargaan',
        'terjamin',
        'kebersihan',
        'keamanan',
        'komunikasi efektif',
        'pelatihan',
    ];

    /**
     * Daftar Kata / Frasa Negatif (Konteks Keluhan, Kendala, Perbaikan RS).
     *
     * @var array<string>
     */
    protected array $negativeWords = [
        'beban kerja',
        'beban',
        'gaji',
        'kurang',
        'stres',
        'lembur',
        'fasilitas kurang',
        'keterbatasan tenaga',
        'kurang tenaga',
        'komunikasi kurang',
        'tidak adil',
        'tekanan',
        'kejelasan karir',
        'ketidakpastian',
        'turnover',
        'waktu istirahat',
        'istirahat kurang',
        'lama',
        'lambat',
        'lelet',
        'antri',
        'antrean',
        'menumpuk',
        'kotor',
        'bau',
        'rusak',
        'panas',
        'sempit',
        'pengap',
        'kasar',
        'jutek',
        'kecewa',
        'kecewa sekali',
        'buruk',
        'ribet',
        'berbelit',
        'sepihak',
        'terhambat',
        'tertunda',
        'telat',
        'kelelahan',
        'capek',
        'keluhan',
        'komplain',
        'tidak jelas',
        'tidak ramah',
        'tidak nyaman',
        'kurang nyaman',
        'kurang bersih',
        'kurang cepat',
        'kurang ramah',
        'kurang transparan',
        'tidak transparan',
        'minim',
        'sulit',
        'susah',
        'bingung',
        'diskriminatif',
        'konflik',
        'masalah',
        'terabaikan',
        'habis',
        'antrian',
        'lelah',
        'penat',
        'berat',
        'kendala',
        'hambatan',
        'bising',
        'gaduh',
        'tumpang tindih',
    ];

    /**
     * Daftar Kata Netral / Operasional (SOP, Kebijakan, dan Standar Prosedur RS).
     *
     * @var array<string>
     */
    protected array $neutralWords = [
        'sop',
        'prosedur',
        'standar',
        'kebijakan',
        'administrasi',
        'rotasi',
        'koordinasi',
        'evaluasi',
        'aturan',
        'rme',
        'simrs',
    ];

    /**
     * Kata-kata Negasi.
     *
     * @var array<string>
     */
    protected array $negationWords = ['tidak', 'bukan', 'kurang', 'belum', 'tanpa', 'jangan'];

    /**
     * Stopwords Bahasa Indonesia dan Entitas Fisik/Konteks Umum RS.
     *
     * @var array<string, bool>
     */
    protected array $stopWords = [
        'yang' => true,
        'di' => true,
        'ke' => true,
        'dari' => true,
        'ini' => true,
        'itu' => true,
        'untuk' => true,
        'pada' => true,
        'adalah' => true,
        'dan' => true,
        'atau' => true,
        'dengan' => true,
        'oleh' => true,
        'karena' => true,
        'juga' => true,
        'saya' => true,
        'kami' => true,
        'kita' => true,
        'mereka' => true,
        'dia' => true,
        'bisa' => true,
        'dapat' => true,
        'akan' => true,
        'telah' => true,
        'sudah' => true,
        'harus' => true,
        'perlu' => true,
        'ada' => true,
        'jadi' => true,
        'dalam' => true,
        'secara' => true,
        'agar' => true,
        'supaya' => true,
        'tentang' => true,
        'sebagai' => true,
        'lebih' => true,
        'sangat' => true,
        'selalu' => true,
        'banyak' => true,
        'hanya' => true,
        'bila' => true,
        'jika' => true,
        'ketika' => true,
        'saat' => true,
        'karyawan' => true,
        'pegawai' => true,
        'staf' => true,
        'rumah' => true,
        'sakit' => true,
        'rs' => true,
        'hal' => true,
        'saja' => true,
        'masih' => true,
        'bagi' => true,
        'antara' => true,
        'yg' => true,
        'nya' => true,
        'dgn' => true,
        'utk' => true,
        'dg' => true,
        'saling' => true,
        'sebaiknya' => true,
        'semoga' => true,
        'mohon' => true,
        'tolong' => true,
        'terima' => true,
        'kasih' => true,
        'terimakasih' => true,
        'maupun' => true,
        'namun' => true,
        'tetapi' => true,
        'tapi' => true,
        'serta' => true,
        'lain' => true,
        'lainnya' => true,
        'pasien' => true,
        'keluarga' => true,
        'hari' => true,
        'bulan' => true,
        'tahun' => true,
        'orang' => true,
        'antar' => true,
        'sering' => true,
        'berkala' => true,
        'ditambah' => true,
        'diganti' => true,
        'ditingkatkan' => true,
        'diperbaiki' => true,
        'diberikan' => true,
        'baru' => true,
        'jaga' => true,
        'malam' => true,
        'jam' => true,
        'rawat' => true,
        'inap' => true,
        'petugas' => true,
        'fasilitas' => true,
        'ruang' => true,
        'ruangan' => true,
        'stasi' => true,
        'obat' => true,
        'komputer' => true,
        'peralatan' => true,
        'sarana' => true,
        'prasarana' => true,
        'ketersediaan' => true,
        'sistem' => true,
        'manajemen' => true,
        'rawat inap' => true,
        'farmasi' => true,
        'apd' => true,
        'shift' => true,
        'igd' => true,
        'cssd' => true,
        'k3rs' => true,
        'rawat jalan' => true,
        'rekam medis' => true,
        'informasi' => true,
        'laporan' => true,
        'jadwal' => true,
        'tugas' => true,
        'proses' => true,
        'kegiatan' => true,
        'dokumen' => true,
        'data' => true,
    ];

    /**
     * Map lookup internal untuk performa tinggi O(1).
     *
     * @var array<string, bool>
     */
    protected array $positiveMap = [];
    protected array $negativeMap = [];
    protected array $neutralMap = [];

    /**
     * Inisialisasi service dan muat kamus kustom dari database.
     */
    public function __construct()
    {
        $this->positiveMap = array_fill_keys($this->positiveWords, true);
        $this->negativeMap = array_fill_keys($this->negativeWords, true);
        $this->neutralMap = array_fill_keys($this->neutralWords, true);

        $this->loadCustomLexicon();
    }

    /**
     * Muat kamus kosakata sentimen kustom dari database jika tabel tersedia.
     */
    protected function loadCustomLexicon(): void
    {
        try {
            if (Schema::hasTable('sentiment_words')) {
                foreach (SentimentWord::query()->active()->get() as $cw) {
                    $w = strtolower(trim($cw->word));
                    match ($cw->sentiment) {
                        'positive' => $this->positiveMap[$w] = true,
                        'negative' => $this->negativeMap[$w] = true,
                        default => $this->neutralMap[$w] = true,
                    };
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully jika database belum siap / mode testing
        }
    }

    /**
     * Klasifikasikan satu record Response ke sentimen (positive, neutral, negative) & skor.
     *
     * @return array{
     *     sentiment: string,
     *     score: float,
     *     label: string,
     *     positive_tokens: array<string>,
     *     negative_tokens: array<string>,
     *     neutral_tokens: array<string>,
     *     total_words: int
     * }
     */
    public function classifyResponse(Response $response): array
    {
        $like = $this->analyzeText(trim((string) ($response->like_text ?? '')));
        $improve = $this->analyzeText(trim((string) ($response->improve_text ?? '')));

        $posTokens = array_merge($like['positive_tokens'], $improve['positive_tokens']);
        $negTokens = array_merge($like['negative_tokens'], $improve['negative_tokens']);
        $neuTokens = array_merge($like['neutral_tokens'], $improve['neutral_tokens']);

        $posCount = count($posTokens);
        $negCount = count($negTokens);

        [$sentiment, $label] = match (true) {
            $posCount > $negCount => ['positive', 'Positif'],
            $negCount > $posCount => ['negative', 'Negatif'],
            default => ['neutral', 'Netral'],
        };

        $totalSentimentTokens = $posCount + $negCount;
        $score = $totalSentimentTokens > 0
            ? round(($posCount - $negCount) / $totalSentimentTokens, 2)
            : 0.0;

        return [
            'sentiment' => $sentiment,
            'score' => $score,
            'label' => $label,
            'positive_tokens' => $posTokens,
            'negative_tokens' => $negTokens,
            'neutral_tokens' => $neuTokens,
            'total_words' => $like['word_count'] + $improve['word_count'],
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

            foreach (['positive' => &$positiveFreq, 'negative' => &$negativeFreq, 'neutral' => &$neutralFreq] as $type => &$freqMap) {
                foreach ($res["{$type}_tokens"] as $tok) {
                    $t = trim($tok);
                    if ($t !== '' && ! isset($this->stopWords[$t])) {
                        $freqMap[$t] = ($freqMap[$t] ?? 0) + 1;
                    }
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
     *
     * @return array{
     *     word_count: int,
     *     positive_tokens: array<string>,
     *     negative_tokens: array<string>,
     *     neutral_tokens: array<string>
     * }
     */
    protected function analyzeText(string $text): array
    {
        if (trim($text) === '') {
            return [
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

        $posTokens = [];
        $negTokens = [];
        $neuTokens = [];
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
            if ($bigram && isset($this->negativeMap[$bigram])) {
                $negTokens[] = $bigram;
                $skipNext = true;

                continue;
            }
            if ($bigram && isset($this->positiveMap[$bigram])) {
                $posTokens[] = $bigram;
                $skipNext = true;

                continue;
            }
            if ($bigram && isset($this->neutralMap[$bigram])) {
                $neuTokens[] = $bigram;
                $skipNext = true;

                continue;
            }

            // 2. Negasi (misal: "tidak ramah" -> negatif, "tidak menumpuk" -> positif)
            if (in_array($w, $this->negationWords, true) && $nextW !== '') {
                if (isset($this->positiveMap[$nextW])) {
                    $negTokens[] = "{$w} {$nextW}";
                    $skipNext = true;

                    continue;
                }
                if (isset($this->negativeMap[$nextW])) {
                    $posTokens[] = "{$w} {$nextW}";
                    $skipNext = true;

                    continue;
                }
            }

            // 3. Kata Tunggal
            if (isset($this->positiveMap[$w])) {
                $posTokens[] = $w;
            } elseif (isset($this->negativeMap[$w])) {
                $negTokens[] = $w;
            } elseif (isset($this->neutralMap[$w])) {
                $neuTokens[] = $w;
            }
        }

        return [
            'word_count' => $wordCount,
            'positive_tokens' => $posTokens,
            'negative_tokens' => $negTokens,
            'neutral_tokens' => $neuTokens,
        ];
    }
}

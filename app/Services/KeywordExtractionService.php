<?php

namespace App\Services;

class KeywordExtractionService
{
    public function __construct(
        protected WordCloudService $wordCloudService
    ) {}

    /**
     * Ambil Top N kata kunci dari daftar frekuensi kata.
     *
     * @param  array<string, int>  $frequencies
     * @return array<string, int>
     */
    public function getTopKeywords(array $frequencies, int $limit = 10): array
    {
        $filtered = [];
        foreach ($frequencies as $word => $count) {
            $w = trim((string) $word);
            if ($w !== '' && ! $this->wordCloudService->isStopWord($w) && mb_strlen($w) >= 3) {
                $filtered[$w] = $count;
            }
        }

        arsort($filtered);

        return array_slice($filtered, 0, $limit, true);
    }
}

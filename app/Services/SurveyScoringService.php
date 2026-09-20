<?php

namespace App\Services;

class SurveyScoringService
{
    /**
     * Helper perhitungan persentase kepuasan skala 1-5.
     */
    public static function percentage(array $scores): float
    {
        return count($scores) ? round((array_sum($scores) / (count($scores) * 5)) * 100, 2) : 0;
    }
}

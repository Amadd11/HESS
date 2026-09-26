<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Period;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyResponseService
{
    /**
     * Simpan jawaban survei responden anonim secara transaksional.
     */
    public function save(Period $period, array $data): Response
    {
        return DB::transaction(function () use ($period, $data) {
            $questions = Question::with('category')->active()->get()->keyBy('id');
            $answers = $data['answers'] ?? [];
            $feedbackData = $data['feedback'] ?? [];
            $overall = $data['overall'] ?? [];
            $profile = $data['profile'] ?? [];

            $allValues = [];
            $aspectScores = [];

            foreach ($answers as $key => $val) {
                $q = $questions[$key] ?? $questions->firstWhere('code', $key);
                if (! $q) {
                    continue;
                }

                $val = (int) $val;
                $allValues[] = $val;

                $catCode = $q->category?->code;
                if ($catCode) {
                    $aspectScores[$catCode] = $aspectScores[$catCode] ?? [];
                    $aspectScores[$catCode][] = $val;
                }
            }

            // Hitung Indeks Kepuasan Pegawai (skala 1–4): (Total Skor / (Jumlah Butir * 4)) * 100
            $generalScore = count($allValues) ? round((array_sum($allValues) / (count($allValues) * 4)) * 100, 2) : 0.00;

            // Rangkum feedback kualitatif per unsur ke like_text (alasan) dan improve_text (saran)
            $likeParts = [];
            $improveParts = [];

            if (! empty($feedbackData) && is_array($feedbackData)) {
                foreach ($feedbackData as $aspectName => $entry) {
                    $reason = trim($entry['reason'] ?? '');
                    $suggestion = trim($entry['suggestion'] ?? '');
                    if ($reason !== '') {
                        $likeParts[] = "[$aspectName] $reason";
                    }
                    if ($suggestion !== '') {
                        $improveParts[] = "[$aspectName] $suggestion";
                    }
                }
            }

            $likeText = ! empty($likeParts) ? implode("\n\n", $likeParts) : (! empty($overall['like_text']) ? trim($overall['like_text']) : null);
            $improveText = ! empty($improveParts) ? implode("\n\n", $improveParts) : (! empty($overall['improve_text']) ? trim($overall['improve_text']) : null);

            $defaultNps = round(($generalScore / 100) * 10);
            $npsScore = isset($overall['nps_score']) && $overall['nps_score'] !== '' ? (int) $overall['nps_score'] : (int) $defaultNps;

            $completedAt = ! empty($data['completed_at']) ? Carbon::parse($data['completed_at']) : Carbon::now();

            $response = Response::create([
                'period_id' => $period->id,
                'profession' => $profile['profession'] ?? '',
                'directorate' => $profile['directorate'] ?? null,
                'unit' => $profile['unit'] ?? '',
                'status' => $profile['status'] ?? '',
                'tenure' => $profile['tenure'] ?? '',
                'age' => $profile['age'] ?? null,
                'gender' => $profile['gender'] ?? null,
                'education' => $profile['education'] ?? null,
                'income' => $profile['income'] ?? null,
                'nps_score' => $npsScore,
                'like_text' => $likeText,
                'improve_text' => $improveText,
                'feedback_data' => ! empty($feedbackData) ? $feedbackData : null,
                'general_score' => $generalScore,
                'nps_category' => $npsScore >= 9 ? 'promoter' : ($npsScore >= 7 ? 'passive' : 'detractor'),
                'completed_at' => $completedAt,
                'created_at' => $completedAt,
                'updated_at' => $completedAt,
            ]);

            $records = [];
            foreach ($answers as $key => $val) {
                $q = $questions[$key] ?? $questions->firstWhere('code', $key);
                if ($q) {
                    $records[] = [
                        'response_id' => $response->id,
                        'question_id' => $q->id,
                        'score' => (int) $val,
                        'created_at' => $completedAt,
                        'updated_at' => $completedAt,
                    ];
                }
            }

            Answer::insert($records);

            return $response;
        });
    }

    /**
     * Alias method untuk kemudahan pemanggilan terpisah.
     */
    public function saveResponse(Period $period, array $profile, array $overall, array $answers, ?Carbon $completedAt = null, ?array $feedback = null): Response
    {
        return $this->save($period, [
            'profile' => $profile,
            'overall' => $overall,
            'answers' => $answers,
            'feedback' => $feedback,
            'completed_at' => $completedAt,
        ]);
    }
}

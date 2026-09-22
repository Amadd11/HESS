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
            $overall = $data['overall'] ?? [];
            $profile = $data['profile'] ?? [];

            $scores = ['intrinsic' => [], 'extrinsic' => [], 'general' => [], 'hospital' => []];

            foreach ($answers as $key => $val) {
                $q = $questions[$key] ?? $questions->firstWhere('code', $key);
                if (! $q) {
                    continue;
                }

                $val = (int) $val;
                if ($q->subscale === 'intrinsic') {
                    $scores['intrinsic'][] = $val;
                }
                if ($q->subscale === 'extrinsic') {
                    $scores['extrinsic'][] = $val;
                }
                if ($q->category?->type === 'msq') {
                    $scores['general'][] = $val;
                }
                if ($q->category?->type === 'hospital') {
                    $scores['hospital'][] = $val;
                }
            }

            $calcPct = fn (array $items) => count($items) ? round((array_sum($items) / (count($items) * 5)) * 100, 2) : 0;
            $npsScore = (int) ($overall['nps_score'] ?? 0);

            $completedAt = ! empty($data['completed_at']) ? Carbon::parse($data['completed_at']) : Carbon::now();

            $response = Response::create([
                'period_id' => $period->id,
                'profession' => $profile['profession'] ?? '',
                'unit' => $profile['unit'] ?? '',
                'status' => $profile['status'] ?? '',
                'tenure' => $profile['tenure'] ?? '',
                'overall_score' => (int) ($overall['overall_score'] ?? 0),
                'nps_score' => $npsScore,
                'like_text' => ! empty($overall['like_text']) ? trim($overall['like_text']) : null,
                'improve_text' => ! empty($overall['improve_text']) ? trim($overall['improve_text']) : null,
                'intrinsic_score' => $calcPct($scores['intrinsic']),
                'extrinsic_score' => $calcPct($scores['extrinsic']),
                'general_score' => $calcPct($scores['general']),
                'hospital_score' => $calcPct($scores['hospital']),
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
    public function saveResponse(Period $period, array $profile, array $overall, array $answers, ?Carbon $completedAt = null): Response
    {
        return $this->save($period, [
            'profile' => $profile,
            'overall' => $overall,
            'answers' => $answers,
            'completed_at' => $completedAt,
        ]);
    }
}

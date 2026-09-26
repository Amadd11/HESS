<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Question;
use App\Models\Response;
use Tests\TestCase;

class SurveySubmissionTest extends TestCase
{
    public function test_survey_page_loads_with_active_questions(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('HESS');
        $response->assertSee('Profil Pegawai');
        $response->assertSee('Direktorat');
    }

    public function test_survey_submission_persists_response_and_answers_with_4_point_scale(): void
    {
        $period = Period::active()->first() ?? Period::factory()->create(['is_active' => true]);
        $questions = Question::active()->get();

        $this->assertCount(24, $questions);

        $answers = [];
        foreach ($questions as $q) {
            $answers[$q->id] = 4; // All Sangat Setuju
        }

        $feedback = [];
        $aspects = [
            'Lingkungan Kerja',
            'Hubungan dengan Atasan',
            'Penghargaan dan Pengukuran Kerja',
            'Kesempatan Pengembangan Karir',
            'Gaji dan Kompensasi',
            'Keseimbangan Kerja dan Kehidupan / Work Life Balance',
            'Komunikasi dalam Rumah Sakit',
            'Budaya Rumah Sakit',
        ];
        foreach ($aspects as $aspect) {
            $feedback[$aspect] = [
                'reason' => 'Lingkungan kerja sangat kolaboratif dan kondusif.',
                'suggestion' => 'Pertahankan sarana prasarana yang sudah baik.',
            ];
        }

        $payload = [
            'profile' => [
                'profession' => 'Dokter / Medis',
                'directorate' => 'Direktorat Medik dan Keperawatan',
                'unit' => 'Instalasi Gawat Darurat',
                'status' => 'PNS',
                'tenure' => '1–3 tahun',
                'age' => '25–35 tahun',
                'gender' => 'Laki-laki',
                'education' => 'Profesi (Dokter / Ners / Apoteker / dll.)',
                'income' => 'Rp 5.000.001 – Rp 10.000.000',
            ],
            'answers' => $answers,
            'feedback' => $feedback,
            'overall' => [
                'nps_score' => 10,
            ],
        ];

        $submitResponse = $this->postJson(route('survey.submit'), $payload);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('responses', [
            'period_id' => $period->id,
            'profession' => 'Dokter / Medis',
            'directorate' => 'Direktorat Medik dan Keperawatan',
            'unit' => 'Instalasi Gawat Darurat',
            'age' => '25–35 tahun',
            'gender' => 'Laki-laki',
            'education' => 'Profesi (Dokter / Ners / Apoteker / dll.)',
            'income' => 'Rp 5.000.001 – Rp 10.000.000',
            'general_score' => 100.00, // (24 * 4) / (24 * 4) * 100
        ]);

        $savedResponse = Response::latest('id')->first();
        $this->assertNotNull($savedResponse->feedback_data);
        $this->assertStringContainsString('Lingkungan Kerja', $savedResponse->like_text);
        $this->assertStringContainsString('Pertahankan', $savedResponse->improve_text);
        $this->assertCount(24, $savedResponse->answers);
    }
}

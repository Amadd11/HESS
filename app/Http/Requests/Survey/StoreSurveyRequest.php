<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Tentukan apakah responden anonim diizinkan mengirim survei.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Seluruh aturan validasi kuesioner survei kepuasan pegawai.
     *
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->profileRules(),
            ...$this->answerRules(),
            ...$this->overallRules(),
        ];
    }

    /**
     * Aturan validasi profil demografi pegawai.
     *
     * @return array<string, string>
     */
    protected function profileRules(): array
    {
        return [
            'profile.profession' => 'required|string|max:100',
            'profile.unit' => 'required|string|max:100',
            'profile.status' => 'required|string|max:100',
            'profile.tenure' => 'required|string|max:100',
        ];
    }

    /**
     * Aturan validasi butir jawaban kuesioner (dinamis sesuai jumlah soal aktif).
     *
     * @return array<string, array<int, mixed>|string>
     */
    protected function answerRules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => 'required|integer|between:1,5',
        ];
    }

    /**
     * Aturan validasi penilaian keseluruhan dan masukan kualitatif.
     *
     * @return array<string, string>
     */
    protected function overallRules(): array
    {
        return [
            'overall.overall_score' => 'required|integer|between:1,5',
            'overall.nps_score' => 'required|integer|between:0,10',
            'overall.like_text' => 'nullable|string|max:2000',
            'overall.improve_text' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Kustomisasi nama atribut agar pesan error ramah dan mudah dibaca.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'profile.profession' => 'kelompok profesi',
            'profile.unit' => 'unit kerja',
            'profile.status' => 'status kepegawaian',
            'profile.tenure' => 'masa kerja',
            'answers' => 'seluruh pertanyaan kuesioner',
            'answers.*' => 'jawaban butir pertanyaan',
            'overall.overall_score' => 'skor kepuasan keseluruhan',
            'overall.nps_score' => 'skor rekomendasi rumah sakit (eNPS)',
            'overall.like_text' => 'masukan hal yang disukai',
            'overall.improve_text' => 'masukan hal yang perlu diperbaiki',
        ];
    }

    /**
     * Pesan kustom validasi dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'answers.required' => 'Seluruh butir pertanyaan kuesioner wajib dijawab.',
            'answers.size' => 'Semua butir pertanyaan kuesioner harus dijawab lengkap tanpa ada yang terlewat.',
            'answers.*.between' => 'Pilihan jawaban skala harus berada antara nilai 1 hingga 5.',
            'overall.overall_score.required' => 'Penilaian kepuasan kerja keseluruhan wajib dipilih.',
            'overall.nps_score.required' => 'Penilaian rekomendasi rumah sakit (eNPS) wajib dipilih.',
            'overall.nps_score.between' => 'Skor eNPS harus berada dalam skala 0 hingga 10.',
        ];
    }

    /**
     * Helper accessor untuk data profil pegawai tervalidasi.
     *
     * @return array{profession: string, unit: string, status: string, tenure: string}
     */
    public function profile(): array
    {
        return $this->validated('profile', []);
    }

    /**
     * Helper accessor untuk seluruh jawaban butir soal kuesioner.
     *
     * @return array<int|string, int>
     */
    public function answers(): array
    {
        return $this->validated('answers', []);
    }

    /**
     * Helper accessor untuk penilaian kepuasan keseluruhan & feedback kualitatif.
     *
     * @return array{overall_score: int, nps_score: int, like_text: ?string, improve_text: ?string}
     */
    public function overall(): array
    {
        return $this->validated('overall', []);
    }
}

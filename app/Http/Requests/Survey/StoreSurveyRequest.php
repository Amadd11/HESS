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
            ...$this->feedbackRules(),
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
            'profile.directorate' => 'required|string|max:100',
            'profile.unit' => 'required|string|max:100',
            'profile.status' => 'required|string|max:100',
            'profile.tenure' => 'required|string|max:100',
            'profile.age' => 'required|string|max:100',
            'profile.gender' => 'required|string|max:100',
            'profile.education' => 'required|string|max:100',
            'profile.income' => 'required|string|max:100',
        ];
    }

    /**
     * Aturan validasi butir jawaban kuesioner (dinamis sesuai jumlah soal aktif, skala 1-4).
     *
     * @return array<string, array<int, mixed>|string>
     */
    protected function answerRules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => 'required|integer|between:1,4',
        ];
    }

    /**
     * Aturan validasi umpan balik kualitatif per unsur/dimensi.
     *
     * @return array<string, string>
     */
    protected function feedbackRules(): array
    {
        return [
            'feedback' => 'nullable|array',
            'feedback.*.reason' => 'nullable|string|max:2000',
            'feedback.*.suggestion' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Aturan validasi penilaian keseluruhan dan masukan kualitatif opsional.
     *
     * @return array<string, string>
     */
    protected function overallRules(): array
    {
        return [
            'overall' => 'nullable|array',
            'overall.nps_score' => 'nullable|integer|between:0,10',
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
            'profile.directorate' => 'direktorat',
            'profile.unit' => 'instalasi / unit kerja',
            'profile.status' => 'status kepegawaian',
            'profile.tenure' => 'masa kerja',
            'profile.age' => 'usia',
            'profile.gender' => 'jenis kelamin',
            'profile.education' => 'latar belakang pendidikan',
            'profile.income' => 'jumlah pendapatan',
            'answers' => 'seluruh pertanyaan kuesioner',
            'answers.*' => 'jawaban butir pertanyaan',
            'feedback.*.reason' => 'alasan penilaian unsur',
            'feedback.*.suggestion' => 'saran perbaikan unsur',
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
            'answers.*.between' => 'Pilihan jawaban skala harus berada antara nilai 1 hingga 4.',
            'overall.nps_score.between' => 'Skor eNPS harus berada dalam skala 0 hingga 10.',
        ];
    }

    /**
     * Helper accessor untuk data profil pegawai tervalidasi.
     *
     * @return array{profession: string, directorate: string, unit: string, status: string, tenure: string}
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
     * Helper accessor untuk seluruh umpan balik per aspek.
     *
     * @return array<string, array{reason: ?string, suggestion: ?string}>
     */
    public function feedback(): array
    {
        return $this->validated('feedback', []);
    }

    /**
     * Helper accessor untuk penilaian kepuasan keseluruhan & feedback kualitatif.
     *
     * @return array{nps_score: ?int, like_text: ?string, improve_text: ?string}
     */
    public function overall(): array
    {
        return $this->validated('overall', []);
    }
}

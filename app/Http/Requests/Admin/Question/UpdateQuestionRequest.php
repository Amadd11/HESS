<?php

namespace App\Http\Requests\Admin\Question;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi perbaruan data butir pertanyaan kuesioner.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $question = $this->route('question');
        $questionId = is_object($question) ? $question->id : $question;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:20', Rule::unique('questions', 'code')->ignore($questionId)],
            'text' => ['required', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Nama atribut untuk pesan error yang lebih ramah.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'kategori kuesioner',
            'code' => 'kode pertanyaan',
            'text' => 'teks butir pertanyaan',
            'order' => 'nomor urutan',
            'is_active' => 'status aktif',
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
            'category_id.required' => 'Kategori kuesioner wajib dipilih.',
            'category_id.exists' => 'Kategori kuesioner yang dipilih tidak valid.',
            'code.required' => 'Kode unik pertanyaan wajib diisi.',
            'code.unique' => 'Kode pertanyaan sudah digunakan oleh soal lain.',
            'text.required' => 'Teks butir pertanyaan kuesioner wajib diisi.',
        ];
    }
}

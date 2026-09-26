<?php

namespace App\Http\Requests\Admin\Question;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi penambahan butir pertanyaan kuesioner.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'code' => ['required', 'string', 'max:20', 'unique:questions,code'],
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
            'code.unique' => 'Kode pertanyaan sudah terdaftar.',
            'text.required' => 'Teks butir pertanyaan kuesioner wajib diisi.',
        ];
    }
}

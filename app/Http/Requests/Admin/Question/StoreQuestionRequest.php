<?php

namespace App\Http\Requests\Admin\Question;

use App\Models\Question;
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
     * Sanitasi dan sinkronisasi input sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $code = strtoupper(trim((string) $this->code));
        $nextOrder = (int) (Question::max('order') ?? 0) + 1;
        $order = $this->filled('order') ? (int) $this->order : $nextOrder;

        // Jika kode dikosongkan, otomatis generate kode default unik H{nomor}
        if ($code === '') {
            $maxCodeNum = Question::withTrashed()->get()->map(function ($q) {
                return preg_match('/^H(\d+)$/i', $q->code, $m) ? (int) $m[1] : 0;
            })->max() ?? 0;
            $code = 'H' . ($maxCodeNum + 1);
        }

        $this->merge([
            'code' => $code,
            'order' => $order,
        ]);
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
            'order' => ['nullable', 'integer', 'min:1'],
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

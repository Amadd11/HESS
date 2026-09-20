<?php

namespace App\Http\Requests\Admin\Period;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi penambahan periode survei baru.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'target' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Nama atribut untuk pesan error ramah pengguna.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama periode',
            'target' => 'target responden',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
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
            'name.required' => 'Nama periode survei wajib diisi.',
            'target.required' => 'Target responden wajib diisi.',
            'target.min' => 'Target responden minimal 1 pegawai.',
            'start_date.required' => 'Tanggal pelaksanaan mulai wajib diisi.',
            'end_date.required' => 'Tanggal pelaksanaan selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ];
    }
}

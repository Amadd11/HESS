<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SentimentFilterRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses untuk request ini.
     */
    public function authorize(): bool
    {
        return auth()->check() || $this->user() !== null;
    }

    /**
     * Aturan validasi untuk filter GET query string.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'period_id' => ['nullable', 'integer', 'exists:periods,id'],
            'unit' => ['nullable', 'string', 'max:100'],
            'profession' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'tenure' => ['nullable', 'string', 'max:100'],
            'sentiment' => ['nullable', 'string', 'in:all,positive,neutral,negative'],
            'search' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Cek apakah ada filter non-default yang diterapkan.
     */
    public function hasActiveFilters(): bool
    {
        return $this->anyFilled(['unit', 'profession', 'status', 'tenure', 'sentiment', 'search']);
    }

    /**
     * Ambil array filter yang telah divalidasi dan dinormalisasi.
     *
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        $data = isset($this->validator) ? $this->validated() : $this->all();

        return array_filter($data, fn ($val) => $val !== null && $val !== '' && $val !== 'all');
    }
}

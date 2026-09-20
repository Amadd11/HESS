<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'code',
        'text',
        'scale',
        'subscale',
        'order',
        'is_active',
    ];

    /**
     * @var array<int, string>
     */
    protected $appends = [
        'labels',
        'category_name',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Pilihan teks skala Likert 1-5 sesuai tipe skala soal.
     *
     * @return array<int, string>
     */
    public function getLabelsAttribute(): array
    {
        if ($this->scale === 'agreement') {
            return [
                'Sangat Tidak Setuju',
                'Tidak Setuju',
                'Ragu-ragu / Netral',
                'Setuju',
                'Sangat Setuju',
            ];
        }

        return [
            'Sangat Tidak Puas',
            'Tidak Puas',
            'Cukup Puas',
            'Puas',
            'Sangat Puas',
        ];
    }

    /**
     * Nama dimensi kategori instrumen.
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? 'Dimensi Kuesioner';
    }

    /**
     * Get the category that this question belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all answers given for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Scope a query to only include active questions.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}

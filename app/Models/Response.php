<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Response extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'period_id',
        'directorate',
        'profession',
        'unit',
        'status',
        'tenure',
        'age',
        'gender',
        'education',
        'income',
        'nps_score',
        'like_text',
        'improve_text',
        'feedback_data',
        'general_score',
        'nps_category',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'period_id' => 'integer',
            'nps_score' => 'integer',
            'feedback_data' => 'array',
            'general_score' => 'float',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the survey period this response belongs to.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * Get all answers in this response.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}

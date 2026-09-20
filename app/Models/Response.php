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
        'profession',
        'unit',
        'status',
        'tenure',
        'overall_score',
        'nps_score',
        'like_text',
        'improve_text',
        'intrinsic_score',
        'extrinsic_score',
        'general_score',
        'hospital_score',
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
            'overall_score' => 'integer',
            'nps_score' => 'integer',
            'intrinsic_score' => 'float',
            'extrinsic_score' => 'float',
            'general_score' => 'float',
            'hospital_score' => 'float',
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

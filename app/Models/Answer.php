<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'response_id',
        'question_id',
        'score',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'response_id' => 'integer',
            'question_id' => 'integer',
            'score' => 'integer',
        ];
    }

    /**
     * Get the response this answer belongs to.
     */
    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }

    /**
     * Get the question answered.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}

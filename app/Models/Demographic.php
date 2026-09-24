<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope data aktif terurut.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope berdasarkan tipe demografi.
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Ambil seluruh opsi demografi aktif yang dikelompokkan berdasarkan tipe.
     *
     * @return array{professions: array<int, string>, directorates: array<int, string>, units: array<int, string>, statuses: array<int, string>, tenures: array<int, string>}
     */
    public static function getGroupedOptions(): array
    {
        $all = static::active()->get()->groupBy('type');

        return [
            'professions' => $all->get('profession', collect())->pluck('name')->toArray(),
            'directorates' => $all->get('directorate', collect())->pluck('name')->toArray(),
            'units' => $all->get('unit', collect())->pluck('name')->toArray(),
            'statuses' => $all->get('status', collect())->pluck('name')->toArray(),
            'tenures' => $all->get('tenure', collect())->pluck('name')->toArray(),
        ];
    }
}

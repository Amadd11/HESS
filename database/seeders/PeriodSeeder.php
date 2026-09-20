<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::updateOrCreate(
            ['slug' => 'survei-kepuasan-2026-s1'],
            [
                'name' => 'Survei Kepuasan Pegawai 2026 — Semester 1',
                'slug' => 'survei-kepuasan-2026-s1',
                'target' => 250,
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->addMonths(2)->endOfMonth(),
                'is_active' => true,
            ]
        );
    }
}

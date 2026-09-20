<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'MSQ-20 (Minnesota Satisfaction Questionnaire)',
                'code' => 'MSQ',
                'type' => 'msq',
                'order' => 1,
            ],
            [
                'name' => 'Leadership & Supervision',
                'code' => 'LS',
                'type' => 'hospital',
                'order' => 2,
            ],
            [
                'name' => 'Workload & Staffing',
                'code' => 'WS',
                'type' => 'hospital',
                'order' => 3,
            ],
            [
                'name' => 'Compensation & Reward',
                'code' => 'CR',
                'type' => 'hospital',
                'order' => 4,
            ],
            [
                'name' => 'Career & Professional Development',
                'code' => 'CD',
                'type' => 'hospital',
                'order' => 5,
            ],
            [
                'name' => 'Work Environment & Facilities',
                'code' => 'EF',
                'type' => 'hospital',
                'order' => 6,
            ],
            [
                'name' => 'Teamwork & Interprofessional Collaboration',
                'code' => 'TC',
                'type' => 'hospital',
                'order' => 7,
            ],
            [
                'name' => 'Psychological & Patient Safety',
                'code' => 'PS',
                'type' => 'hospital',
                'order' => 8,
            ],
            [
                'name' => 'Work-Life Balance',
                'code' => 'WB',
                'type' => 'hospital',
                'order' => 9,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['code' => $category['code']], $category);
        }
    }
}

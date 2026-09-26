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
                'name' => 'Lingkungan Kerja',
                'code' => 'LK',
                'order' => 1,
            ],
            [
                'name' => 'Hubungan dengan Atasan',
                'code' => 'HA',
                'order' => 2,
            ],
            [
                'name' => 'Penghargaan dan Pengukuran Kerja',
                'code' => 'PP',
                'order' => 3,
            ],
            [
                'name' => 'Kesempatan Pengembangan Karir',
                'code' => 'KPK',
                'order' => 4,
            ],
            [
                'name' => 'Gaji dan Kompensasi',
                'code' => 'GK',
                'order' => 5,
            ],
            [
                'name' => 'Keseimbangan Kerja dan Kehidupan / Work Life Balance',
                'code' => 'WLB',
                'order' => 6,
            ],
            [
                'name' => 'Komunikasi dalam Rumah Sakit',
                'code' => 'KRS',
                'order' => 7,
            ],
            [
                'name' => 'Budaya Rumah Sakit',
                'code' => 'BRS',
                'order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['code' => $category['code']], $category);
        }
    }
}

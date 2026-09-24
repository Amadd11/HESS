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
                'type' => 'hospital',
                'order' => 1,
            ],
            [
                'name' => 'Hubungan dengan Atasan',
                'code' => 'HA',
                'type' => 'hospital',
                'order' => 2,
            ],
            [
                'name' => 'Penghargaan dan Pengukuran Kerja',
                'code' => 'PP',
                'type' => 'hospital',
                'order' => 3,
            ],
            [
                'name' => 'Kesempatan Pengembangan Karir',
                'code' => 'KPK',
                'type' => 'hospital',
                'order' => 4,
            ],
            [
                'name' => 'Gaji dan Kompensasi',
                'code' => 'GK',
                'type' => 'hospital',
                'order' => 5,
            ],
            [
                'name' => 'Keseimbangan Kerja dan Kehidupan / Work Life Balance',
                'code' => 'WLB',
                'type' => 'hospital',
                'order' => 6,
            ],
            [
                'name' => 'Komunikasi dalam Rumah Sakit',
                'code' => 'KRS',
                'type' => 'hospital',
                'order' => 7,
            ],
            [
                'name' => 'Budaya Rumah Sakit',
                'code' => 'BRS',
                'type' => 'hospital',
                'order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['code' => $category['code']], $category);
        }
    }
}

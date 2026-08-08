<?php

namespace Database\Seeders;

use App\Models\MediaCategory;
use Illuminate\Database\Seeder;

class MediaCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Antes',
                'code' => 'Before',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Proceso',
                'code' => 'Process',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Fabricación',
                'code' => 'Fabrication',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Instalación',
                'code' => 'Installation',
                'displayOrder' => 4,
            ],
            [
                'name' => 'Detalle',
                'code' => 'Detail',
                'displayOrder' => 5,
            ],
            [
                'name' => 'Resultado final',
                'code' => 'FinalResult',
                'displayOrder' => 6,
            ],
        ];

        foreach ($categories as $category) {
            MediaCategory::updateOrCreate(
                [
                    'code' => $category['code'],
                ],
                [
                    'name' => $category['name'],
                    'displayOrder' => $category['displayOrder'],
                    'isActive' => true,
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\QuoteFileCategory;
use Illuminate\Database\Seeder;

class QuoteFileCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Espacio actual', 'code' => 'CurrentSpace', 'displayOrder' => 1],
            ['name' => 'Imagen de referencia', 'code' => 'ReferenceImage', 'displayOrder' => 2],
            ['name' => 'Plano', 'code' => 'Blueprint', 'displayOrder' => 3],
            ['name' => 'Croquis', 'code' => 'Sketch', 'displayOrder' => 4],
            ['name' => 'Documento', 'code' => 'Document', 'displayOrder' => 5],
            ['name' => 'Otro', 'code' => 'Other', 'displayOrder' => 6],
        ];

        foreach ($items as $item) {
            QuoteFileCategory::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'displayOrder' => $item['displayOrder'],
                    'isActive' => true,
                ]
            );
        }
    }
}
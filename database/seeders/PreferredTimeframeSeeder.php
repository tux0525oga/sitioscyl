<?php

namespace Database\Seeders;

use App\Models\PreferredTimeframe;
use Illuminate\Database\Seeder;

class PreferredTimeframeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Lo antes posible', 'code' => 'AsSoonAsPossible', 'displayOrder' => 1],
            ['name' => 'Este mes', 'code' => 'ThisMonth', 'displayOrder' => 2],
            ['name' => '1 a 3 meses', 'code' => 'OneToThreeMonths', 'displayOrder' => 3],
            ['name' => 'Más adelante', 'code' => 'Later', 'displayOrder' => 4],
            ['name' => 'Solo información', 'code' => 'InformationOnly', 'displayOrder' => 5],
        ];

        foreach ($items as $item) {
            PreferredTimeframe::updateOrCreate(
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
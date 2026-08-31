<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cancelería de aluminio y vidrio',
                'slug' => 'canceleria-aluminio-vidrio',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Vidrio arquitectónico',
                'slug' => 'vidrio-arquitectonico',
                'displayOrder' => 2,
            ],
            [
                'name' => 'PVC y doble vidrio',
                'slug' => 'pvc-doble-vidrio',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Herrería',
                'slug' => 'herreria',
                'displayOrder' => 4,
            ],
            [
                'name' => 'Remodelaciones',
                'slug' => 'remodelaciones',
                'displayOrder' => 5,
            ],
            [
                'name' => 'Acabados',
                'slug' => 'acabados',
                'displayOrder' => 6,
            ],
            [
                'name' => 'Persianas',
                'slug' => 'persianas',
                'displayOrder' => 7,
            ],
            [
                'name' => 'Vitrales',
                'slug' => 'vitrales',
                'displayOrder' => 8,
            ],
            [
                'name' => 'Instalaciones y servicios constructivos',
                'slug' => 'instalaciones-servicios-constructivos',
                'displayOrder' => 9,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                [
                    'slug' => $service['slug'],
                ],
                [
                    'name' => $service['name'],
                    'displayOrder' => $service['displayOrder'],
                    'isPublished' => false,
                    'isFeatured' => false,
                ]
            );
        }
    }
}

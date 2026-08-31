<?php

namespace Database\Seeders;

use App\Models\ContactMethod;
use Illuminate\Database\Seeder;

class ContactMethodSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'WhatsApp', 'code' => 'WhatsApp', 'displayOrder' => 1],
            ['name' => 'Llamada', 'code' => 'PhoneCall', 'displayOrder' => 2],
            ['name' => 'Correo electrónico', 'code' => 'Email', 'displayOrder' => 3],
        ];

        foreach ($items as $item) {
            ContactMethod::updateOrCreate(
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
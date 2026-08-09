<?php

namespace Database\Seeders;

use App\Models\QuoteStatus;
use Illuminate\Database\Seeder;

class QuoteStatusSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Nueva solicitud', 'code' => 'New', 'displayOrder' => 1, 'isClosed' => false],
            ['name' => 'Contactado', 'code' => 'Contacted', 'displayOrder' => 2, 'isClosed' => false],
            ['name' => 'Pendiente de información', 'code' => 'WaitingForInformation', 'displayOrder' => 3, 'isClosed' => false],
            ['name' => 'Visita programada', 'code' => 'VisitScheduled', 'displayOrder' => 4, 'isClosed' => false],
            ['name' => 'Cotización en elaboración', 'code' => 'PreparingQuote', 'displayOrder' => 5, 'isClosed' => false],
            ['name' => 'Cotización enviada', 'code' => 'QuoteSent', 'displayOrder' => 6, 'isClosed' => false],
            ['name' => 'Seguimiento', 'code' => 'FollowUp', 'displayOrder' => 7, 'isClosed' => false],
            ['name' => 'Aceptado', 'code' => 'Accepted', 'displayOrder' => 8, 'isClosed' => true],
            ['name' => 'No aceptado', 'code' => 'Rejected', 'displayOrder' => 9, 'isClosed' => true],
            ['name' => 'Cerrado', 'code' => 'Closed', 'displayOrder' => 10, 'isClosed' => true],
        ];

        foreach ($items as $item) {
            QuoteStatus::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'displayOrder' => $item['displayOrder'],
                    'isClosed' => $item['isClosed'],
                    'isActive' => true,
                ]
            );
        }
    }
}
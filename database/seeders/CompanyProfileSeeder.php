<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        CompanyProfile::updateOrCreate(
            [
                'code' => 'Main',
            ],
            [
                'companyName' => 'Somos Constructivos',
                'slogan' => 'Tu proyecto, nuestro compromiso!',
            ]
        );
    }
}

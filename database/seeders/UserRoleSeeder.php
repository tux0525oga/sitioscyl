<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        UserRole::updateOrCreate(
            [
                'code' => 'Administrator',
            ],
            [
                'name' => 'Administrador',
                'description' => 'Acceso completo al sistema administrativo.',
                'isActive' => true,
            ]
        );

        UserRole::updateOrCreate(
            [
                'code' => 'Editor',
            ],
            [
                'name' => 'Editor',
                'description' => 'Administración de contenido sin acceso a configuración crítica.',
                'isActive' => true,
            ]
        );
    }
}

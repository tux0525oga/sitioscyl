<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Residencial', 'slug' => 'residencial'],
            ['name' => 'Comercial', 'slug' => 'comercial'],
            ['name' => 'Terraza', 'slug' => 'terraza'],
            ['name' => 'Fachada', 'slug' => 'fachada'],
            ['name' => 'Barandal', 'slug' => 'barandal'],
            ['name' => 'Puerta', 'slug' => 'puerta'],
            ['name' => 'Ventana', 'slug' => 'ventana'],
            ['name' => 'Baño', 'slug' => 'bano'],
            ['name' => 'Cocina', 'slug' => 'cocina'],
            ['name' => 'Control solar', 'slug' => 'control-solar'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                [
                    'slug' => $tag['slug'],
                ],
                [
                    'name' => $tag['name'],
                    'isActive' => true,
                ]
            );
        }
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('tagId')->primary();

            $table->string('name', 120);
            $table->string('slug', 160)->unique();

            $table->boolean('isActive')
                ->default(true)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag');
    }
};
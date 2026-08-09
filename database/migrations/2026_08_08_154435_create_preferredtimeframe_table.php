<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preferredtimeframe', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('preferredTimeframeId')->primary();

            $table->string('name', 100);
            $table->string('code', 80)->unique();

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->boolean('isActive')
                ->default(true)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferredtimeframe');
    }
};
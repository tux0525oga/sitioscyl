<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('userrole', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('userRoleId')->primary();

            $table->string('name', 80);
            $table->string('code', 50)->unique();
            $table->string('description', 255)->nullable();

            $table->boolean('isActive')
                ->default(true)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('userrole');
    }
};

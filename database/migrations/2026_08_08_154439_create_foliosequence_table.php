<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foliosequence', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedSmallInteger('sequenceYear')
                ->primary();

            $table->unsignedInteger('lastNumber')
                ->default(0);

            $table->dateTime('updatedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foliosequence');
    }
};
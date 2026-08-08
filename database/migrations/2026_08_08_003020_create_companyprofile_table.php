<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companyprofile', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('companyProfileId')->primary();

            $table->string('code', 50)->unique();

            $table->string('companyName', 160);
            $table->string('slogan', 255)->nullable();

            $table->string('phoneNumber', 30)->nullable();
            $table->string('whatsAppNumber', 30)->nullable();
            $table->string('contactEmail', 190)->nullable();

            $table->string('addressLine', 255)->nullable();
            $table->string('locationCity', 120)->nullable();
            $table->string('locationState', 120)->nullable();
            $table->string('postalCode', 15)->nullable();

            $table->string('businessHours', 255)->nullable();

            $table->foreignUlid('logoMediaId')->nullable();
            $table->foreignUlid('monogramMediaId')->nullable();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->foreign('logoMediaId', 'fk_companyprofile_logo')
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('monogramMediaId', 'fk_companyprofile_monogram')
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companyprofile');
    }
};
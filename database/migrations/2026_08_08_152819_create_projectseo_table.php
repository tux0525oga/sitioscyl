<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projectseo', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('projectSeoId')->primary();

            $table->foreignUlid('projectId')->unique();

            $table->string('metaTitle', 180)->nullable();
            $table->string('metaDescription', 320)->nullable();

            $table->string('canonicalUrl', 500)->nullable();

            $table->string('socialTitle', 180)->nullable();
            $table->string('socialDescription', 320)->nullable();

            $table->foreignUlid('socialImageId')->nullable();

            $table->boolean('robotsIndex')
                ->default(true);

            $table->boolean('robotsFollow')
                ->default(true);

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->foreign(
                'projectId',
                'fk_projectseo_project'
            )
                ->references('projectId')
                ->on('project')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'socialImageId',
                'fk_projectseo_socialimage'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projectseo');
    }
};
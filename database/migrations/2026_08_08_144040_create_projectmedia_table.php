<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projectmedia', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('projectMediaId')->primary();

            $table->foreignUlid('projectId');
            $table->foreignUlid('mediaId');

            $table->foreignUlid('mediaCategoryId')
                ->nullable();

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->boolean('isFeatured')
                ->default(false)
                ->index();

            $table->dateTime('createdAt');

            $table->foreign(
                'projectId',
                'fk_projectmedia_project'
            )
                ->references('projectId')
                ->on('project')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'mediaId',
                'fk_projectmedia_mediaasset'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign(
                'mediaCategoryId',
                'fk_projectmedia_mediacategory'
            )
                ->references('mediaCategoryId')
                ->on('mediacategory')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unique(
                ['projectId', 'mediaId'],
                'uq_projectmedia_project_media'
            );

            $table->index(
                ['projectId', 'displayOrder'],
                'idx_projectmedia_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projectmedia');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('projectId')->primary();

            $table->string('name', 190);
            $table->string('slug', 190)->unique();

            $table->string('shortDescription', 500)->nullable();
            $table->mediumText('description')->nullable();

            $table->text('challengeDescription')->nullable();
            $table->text('solutionDescription')->nullable();

            $table->string('locationCity', 120)->nullable();
            $table->string('locationState', 120)->nullable();

            $table->unsignedSmallInteger('projectYear')->nullable();

            $table->foreignUlid('featuredImageId')->nullable();

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->boolean('isFeatured')
                ->default(false)
                ->index();

            $table->boolean('isPublished')
                ->default(false)
                ->index();

            $table->dateTime('publishedAt')->nullable();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->softDeletes('deletedAt');

            $table->foreign(
                'featuredImageId',
                'fk_project_featuredimage'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(
                ['isPublished', 'displayOrder'],
                'idx_project_publish_order'
            );

            $table->index(
                ['locationState', 'locationCity'],
                'idx_project_location'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project');
    }
};

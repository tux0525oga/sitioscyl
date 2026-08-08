<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('serviceId')->primary();

            $table->string('name', 160);
            $table->string('slug', 190)->unique();

            $table->string('shortDescription', 500)->nullable();
            $table->mediumText('description')->nullable();

            $table->string('heroTitle', 255)->nullable();
            $table->string('heroSubtitle', 500)->nullable();

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
                'fk_service_featuredimage'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(
                ['isPublished', 'displayOrder'],
                'idx_service_publish_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
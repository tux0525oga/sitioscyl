<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicesolution', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('serviceSolutionId')->primary();

            $table->foreignUlid('serviceId');

            $table->string('name', 160);
            $table->string('slug', 190);

            $table->string('shortDescription', 500)->nullable();
            $table->text('description')->nullable();

            $table->foreignUlid('featuredImageId')->nullable();

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->boolean('isPublished')
                ->default(true)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->foreign(
                'serviceId',
                'fk_servicesolution_service'
            )
                ->references('serviceId')
                ->on('service')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'featuredImageId',
                'fk_servicesolution_featuredimage'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unique(
                ['serviceId', 'slug'],
                'uq_servicesolution_service_slug'
            );

            $table->index(
                ['serviceId', 'displayOrder'],
                'idx_servicesolution_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicesolution');
    }
};

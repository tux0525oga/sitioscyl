<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicemedia', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('serviceMediaId')->primary();

            $table->foreignUlid('serviceId');
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
                'serviceId',
                'fk_servicemedia_service'
            )
                ->references('serviceId')
                ->on('service')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'mediaId',
                'fk_servicemedia_mediaasset'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign(
                'mediaCategoryId',
                'fk_servicemedia_mediacategory'
            )
                ->references('mediaCategoryId')
                ->on('mediacategory')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unique(
                ['serviceId', 'mediaId'],
                'uq_servicemedia_service_media'
            );

            $table->index(
                ['serviceId', 'displayOrder'],
                'idx_servicemedia_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicemedia');
    }
};

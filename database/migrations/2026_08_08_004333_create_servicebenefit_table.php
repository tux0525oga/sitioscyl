<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicebenefit', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('serviceBenefitId')->primary();

            $table->foreignUlid('serviceId');

            $table->string('title', 160);
            $table->string('description', 500)->nullable();
            $table->string('iconKey', 100)->nullable();

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->boolean('isPublished')
                ->default(true)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->foreign(
                'serviceId',
                'fk_servicebenefit_service'
            )
                ->references('serviceId')
                ->on('service')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->index(
                ['serviceId', 'displayOrder'],
                'idx_servicebenefit_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicebenefit');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicefaq', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('serviceFaqId')->primary();

            $table->foreignUlid('serviceId');
            $table->foreignUlid('faqId');

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->dateTime('createdAt');

            $table->foreign(
                'serviceId',
                'fk_servicefaq_service'
            )
                ->references('serviceId')
                ->on('service')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'faqId',
                'fk_servicefaq_faq'
            )
                ->references('faqId')
                ->on('faq')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(
                ['serviceId', 'faqId'],
                'uq_servicefaq_service_faq'
            );

            $table->index(
                ['serviceId', 'displayOrder'],
                'idx_servicefaq_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicefaq');
    }
};

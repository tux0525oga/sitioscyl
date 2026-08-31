<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quoterequestservice', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('quoteRequestServiceId')->primary();

            $table->foreignUlid('quoteRequestId');
            $table->foreignUlid('serviceId');

            $table->dateTime('createdAt');

            $table->foreign(
                'quoteRequestId',
                'fk_quoterequestservice_quoterequest'
            )
                ->references('quoteRequestId')
                ->on('quoterequest')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'serviceId',
                'fk_quoterequestservice_service'
            )
                ->references('serviceId')
                ->on('service')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unique(
                ['quoteRequestId', 'serviceId'],
                'uq_quoterequestservice_quote_service'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quoterequestservice');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotestatushistory', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('quoteStatusHistoryId')->primary();

            $table->foreignUlid('quoteRequestId');
            $table->foreignUlid('quoteStatusId');
            $table->foreignUlid('changedBy')->nullable();

            $table->dateTime('createdAt');

            $table->foreign(
                'quoteRequestId',
                'fk_quotestatushistory_quoterequest'
            )
                ->references('quoteRequestId')
                ->on('quoterequest')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'quoteStatusId',
                'fk_quotestatushistory_quotestatus'
            )
                ->references('quoteStatusId')
                ->on('quotestatus')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign(
                'changedBy',
                'fk_quotestatushistory_useraccount'
            )
                ->references('userId')
                ->on('useraccount')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(
                ['quoteRequestId', 'createdAt'],
                'idx_quotestatushistory_quote_date'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotestatushistory');
    }
};
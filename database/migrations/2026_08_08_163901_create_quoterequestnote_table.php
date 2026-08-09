<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quoterequestnote', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('quoteRequestNoteId')->primary();

            $table->foreignUlid('quoteRequestId');
            $table->foreignUlid('userId')->nullable();

            $table->text('noteText');

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->foreign(
                'quoteRequestId',
                'fk_quoterequestnote_quoterequest'
            )
                ->references('quoteRequestId')
                ->on('quoterequest')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'userId',
                'fk_quoterequestnote_useraccount'
            )
                ->references('userId')
                ->on('useraccount')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(
                ['quoteRequestId', 'createdAt'],
                'idx_quoterequestnote_quote_date'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quoterequestnote');
    }
};
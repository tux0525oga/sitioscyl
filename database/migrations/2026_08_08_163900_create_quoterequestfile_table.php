<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quoterequestfile', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('quoteRequestFileId')->primary();

            $table->foreignUlid('quoteRequestId');
            $table->foreignUlid('quoteFileCategoryId')->nullable();

            $table->string('storageDisk', 50)->default('local');
            $table->string('storagePath', 1000);

            $table->string('fileName', 255);
            $table->string('originalFileName', 255)->nullable();

            $table->string('mimeType', 120);
            $table->unsignedBigInteger('fileSize');

            $table->char('sha256', 64)->nullable()->index();

            $table->dateTime('createdAt');

            $table->foreign(
                'quoteRequestId',
                'fk_quoterequestfile_quoterequest'
            )
                ->references('quoteRequestId')
                ->on('quoterequest')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'quoteFileCategoryId',
                'fk_quoterequestfile_category'
            )
                ->references('quoteFileCategoryId')
                ->on('quotefilecategory')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(
                ['quoteRequestId', 'createdAt'],
                'idx_quoterequestfile_quote_date'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quoterequestfile');
    }
};
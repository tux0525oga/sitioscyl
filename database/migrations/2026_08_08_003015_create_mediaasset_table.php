<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mediaasset', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('mediaId')->primary();

            $table->foreignUlid('uploadedBy')->nullable();

            $table->string('storageDisk', 50)->default('public');
            $table->string('storagePath', 1000);

            $table->string('fileName', 255);
            $table->string('originalFileName', 255)->nullable();

            $table->string('mimeType', 120);
            $table->string('fileExtension', 20)->nullable();

            $table->unsignedBigInteger('fileSize');

            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            $table->char('sha256', 64)->nullable()->index();

            $table->string('title', 255)->nullable();
            $table->string('altText', 500)->nullable();
            $table->text('description')->nullable();

            $table->boolean('isPublic')
                ->default(false)
                ->index();

            $table->boolean('isPublished')
                ->default(false)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->softDeletes('deletedAt');

            $table->foreign('uploadedBy', 'fk_mediaasset_useraccount')
                ->references('userId')
                ->on('useraccount')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(
                ['isPublic', 'isPublished'],
                'idx_mediaasset_visibility'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mediaasset');
    }
};
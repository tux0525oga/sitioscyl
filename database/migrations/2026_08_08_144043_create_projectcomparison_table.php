<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projectcomparison', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('projectComparisonId')->primary();

            $table->foreignUlid('projectId');

            $table->foreignUlid('beforeMediaId');
            $table->foreignUlid('afterMediaId');

            $table->string('title', 255)->nullable();
            $table->string('description', 500)->nullable();

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->boolean('isPublished')
                ->default(true)
                ->index();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->foreign(
                'projectId',
                'fk_projectcomparison_project'
            )
                ->references('projectId')
                ->on('project')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'beforeMediaId',
                'fk_projectcomparison_beforemedia'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign(
                'afterMediaId',
                'fk_projectcomparison_aftermedia'
            )
                ->references('mediaId')
                ->on('mediaasset')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unique(
                ['projectId', 'beforeMediaId', 'afterMediaId'],
                'uq_projectcomparison_media'
            );

            $table->index(
                ['projectId', 'displayOrder'],
                'idx_projectcomparison_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projectcomparison');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projecttag', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('projectTagId')->primary();

            $table->foreignUlid('projectId');
            $table->foreignUlid('tagId');

            $table->dateTime('createdAt');

            $table->foreign(
                'projectId',
                'fk_projecttag_project'
            )
                ->references('projectId')
                ->on('project')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'tagId',
                'fk_projecttag_tag'
            )
                ->references('tagId')
                ->on('tag')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(
                ['projectId', 'tagId'],
                'uq_projecttag_project_tag'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projecttag');
    }
};
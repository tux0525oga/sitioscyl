<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projectservice', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('projectServiceId')->primary();

            $table->foreignUlid('projectId');
            $table->foreignUlid('serviceId');

            $table->unsignedSmallInteger('displayOrder')
                ->default(0);

            $table->dateTime('createdAt');

            $table->foreign(
                'projectId',
                'fk_projectservice_project'
            )
                ->references('projectId')
                ->on('project')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'serviceId',
                'fk_projectservice_service'
            )
                ->references('serviceId')
                ->on('service')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unique(
                ['projectId', 'serviceId'],
                'uq_projectservice_project_service'
            );

            $table->index(
                ['serviceId', 'displayOrder'],
                'idx_projectservice_service_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projectservice');
    }
};
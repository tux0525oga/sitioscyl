<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quoterequest', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('quoteRequestId')->primary();

            $table->string('folio', 30)->unique();

            $table->foreignUlid('contactId');

            $table->text('description')->nullable();

            $table->string('locationCity', 120)->nullable();
            $table->string('locationState', 120)->nullable();
            $table->string('locationNeighborhood', 160)->nullable();

            $table->foreignUlid('preferredTimeframeId')
                ->nullable();

            $table->foreignUlid('referenceProjectId')
                ->nullable();

            $table->string('sourcePage', 255)->nullable();
            $table->string('sourceUrl', 1000)->nullable();

            $table->foreignUlid('quoteStatusId');

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->softDeletes('deletedAt');

            $table->foreign(
                'contactId',
                'fk_quoterequest_contact'
            )
                ->references('contactId')
                ->on('contact')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign(
                'preferredTimeframeId',
                'fk_quoterequest_timeframe'
            )
                ->references('preferredTimeframeId')
                ->on('preferredtimeframe')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign(
                'referenceProjectId',
                'fk_quoterequest_referenceproject'
            )
                ->references('projectId')
                ->on('project')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign(
                'quoteStatusId',
                'fk_quoterequest_quotestatus'
            )
                ->references('quoteStatusId')
                ->on('quotestatus')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index(
                ['quoteStatusId', 'createdAt'],
                'idx_quoterequest_status_date'
            );

            $table->index(
                ['locationState', 'locationCity'],
                'idx_quoterequest_location'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quoterequest');
    }
};
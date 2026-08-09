<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('contactId')->primary();

            $table->string('firstName', 120);
            $table->string('lastName', 160)->nullable();

            $table->string('phoneNumber', 30)
                ->nullable()
                ->index();

            $table->string('whatsAppNumber', 30)
                ->nullable()
                ->index();

            $table->string('email', 190)
                ->nullable()
                ->index();

            $table->foreignUlid('preferredContactMethodId')
                ->nullable();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->softDeletes('deletedAt');

            $table->foreign(
                'preferredContactMethodId',
                'fk_contact_contactmethod'
            )
                ->references('contactMethodId')
                ->on('contactmethod')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
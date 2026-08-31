<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('useraccount', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->ulid('userId')->primary();

            $table->foreignUlid('userRoleId');

            $table->string('firstName', 100);
            $table->string('lastName', 150)->nullable();

            $table->string('email', 190)->unique();
            $table->string('passwordHash', 255);

            $table->boolean('isActive')
                ->default(true)
                ->index();

            $table->dateTime('lastLoginAt')->nullable();

            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->softDeletes('deletedAt');

            $table->foreign('userRoleId', 'fk_useraccount_userrole')
                ->references('userRoleId')
                ->on('userrole')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('useraccount');
    }
};

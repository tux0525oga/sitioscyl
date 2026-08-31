<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'companyprofile',
            function (Blueprint $table): void {
                $table
                    ->char('homeHeroMediaId', 26)
                    ->nullable()
                    ->after('monogramMediaId');

                $table
                    ->foreign(
                        'homeHeroMediaId',
                        'fk_companyprofile_home_hero_media'
                    )
                    ->references('mediaId')
                    ->on('mediaasset')
                    ->nullOnDelete();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'companyprofile',
            function (Blueprint $table): void {
                $table->dropForeign(
                    'fk_companyprofile_home_hero_media'
                );

                $table->dropColumn(
                    'homeHeroMediaId'
                );
            }
        );
    }
};

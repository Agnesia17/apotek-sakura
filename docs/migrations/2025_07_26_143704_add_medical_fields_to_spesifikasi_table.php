<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spesifikasi', function (Blueprint $table) {
            $table->text('komposisi')->nullable();
            $table->text('indikasi')->nullable();
            $table->text('dosis')->nullable();
            $table->text('cara_pakai')->nullable();
            $table->text('efek_samping')->nullable();
            $table->text('kontraindikasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spesifikasi', function (Blueprint $table) {
            $table->dropColumn([
                'komposisi',
                'indikasi', 
                'dosis',
                'cara_pakai',
                'efek_samping',
                'kontraindikasi'
            ]);
        });
    }
};

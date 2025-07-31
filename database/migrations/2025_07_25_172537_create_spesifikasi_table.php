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
        Schema::create('spesifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_obat');
            $table->string('kandungan')->nullable();
            $table->string('bentuk_sediaan')->nullable();
            $table->string('kemasan')->nullable();
            $table->string('satuan')->nullable();
            $table->string('cara_kerja')->nullable();
            $table->string('penyimpanan')->nullable();
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
            $table->primary('id_obat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spesifikasi');
    }
};
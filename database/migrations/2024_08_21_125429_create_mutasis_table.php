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
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_mutasi', 100)->nullable(false);
            $table->integer('jumlah')->nullable(false);
            $table->date('tanggal')->nullable(false);
            $table->unsignedBigInteger('barang_id')->nullable(false);
            $table->timestamps();
            $table->foreign('barang_id')->on('barangs')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};

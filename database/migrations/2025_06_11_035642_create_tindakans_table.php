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
        Schema::create('tindakans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id')->references('id')->on('pasiens')->onDelete('cascade');
            $table->unsignedBigInteger('dpjp_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('nama_tindakan');
            $table->enum('divisi', [
                'Jantung Dewasa',
                'Jantung Pediatri & Kongengital',
                'Toraks',
                'Vaskular',
                'Endovaskular'
            ]);
            $table->string('diagnosa');
            $table->timestamp('tanggal_operasi');
            $table->text('laporan_tindakan');
            $table->text('foto_tindakan')->nullable();
            $table->boolean('verifikasi')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakans');
    }
};

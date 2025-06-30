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
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id')->references('id')->on('pasiens')->onDelete('cascade');
            $table->unsignedBigInteger('tindakan_id')->references('id')->on('tindakans')->onDelete('cascade');
            $table->timestamp('tanggal_conference');
            $table->text('hasil_conference');
            $table->boolean('kesesuaian')->default(false);
            $table->text('realisasi_tindakan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};

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
            $table->unsignedBigInteger('operator_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('asisten1_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('asisten2_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('on_loop_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('tanggal_operasi');
            $table->string('relealisasi_tindakan');
            $table->string('kesesuaian');
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

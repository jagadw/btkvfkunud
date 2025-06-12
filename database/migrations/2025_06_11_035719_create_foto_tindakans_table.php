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
        Schema::create('foto_tindakans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tindakan_id')->references('id')->on('tindakans')->onDelete('cascade');
            $table->string('foto');
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_tindakans');
    }
};

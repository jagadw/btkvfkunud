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
        Schema::create('tindakan_asistens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tindakan_id');
            $table->foreign('tindakan_id')->references('id')->on('tindakans')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('tipe', ['asisten', 'onloop']); 
            $table->integer('urutan')->nullable(); 
            $table->enum('role', ['Mandiri', 'Bimbingan', 'Observer', 'Asisten'])->nullable(); 
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindakan_asistens');
    }
};

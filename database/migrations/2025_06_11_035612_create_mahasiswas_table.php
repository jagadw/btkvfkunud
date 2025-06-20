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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->text('nama');
            $table->text('inisial_residen');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};

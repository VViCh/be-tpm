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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_group')->nullable();
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('jenis_file');
            $table->string('path_file');
            $table->integer('ukuran_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};

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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('nama_group');
            $table->string('password_group');
            $table->string('status_group');
            $table->string('nama_leader');
            $table->string('email_leader')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('nomor_wa_leader')->unique();
            $table->string('id_line_leader');
            $table->string('github_leader');
            $table->string('tmp_lahir_leader');
            $table->date('tgl_lahir_leader');
            $table->enum('is_binusian', ['binusian', 'non-binusian'])->default('non-binusian');
            $table->string('cv')->nullable(); 
            $table->string('flazz_card')->nullable();
            $table->string('id_card')->nullable();    
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};

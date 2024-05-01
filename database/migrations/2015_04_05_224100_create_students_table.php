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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser');
            $table->enum('level',['l3','m2'])->nullable();
            $table->enum('specialite',['isil','acad','gtr'])->default('isil');
            $table->string('section');
            $table->string('uniqueCode');
            $table->tinyInteger('haveBinom')->default(0);// 0:Dont have and dont lock for binom / 1:Have binom / -1:Lock for a random benome
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

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
        Schema::create('binoms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idEtu1');
            $table->unsignedBigInteger('idEtu2');
            $table->enum('type',['request','valid']);
            $table->foreign('idEtu1')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('idEtu2')->references('id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binoms');
    }
};

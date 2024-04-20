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
        Schema::create('soutnances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPfe');
            $table->integer('site');
            $table->string('salle');
            $table->dateTime('date');
            $table->foreign('idPfe')->references('id')->on('pfes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soutnances');
    }
};

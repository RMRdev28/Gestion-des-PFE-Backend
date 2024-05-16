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
        Schema::create('validation_pves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPfe');
            $table->unsignedBigInteger('idProf');
            $table->tinyInteger('decision')->default(0);
            $table->String('comment')->nullable();
            $table->foreign('idProf')->references('id')->on('profs')->onDelete('cascade');
            $table->foreign('idPfe')->references('id')->on('pfes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_pves');
    }
};

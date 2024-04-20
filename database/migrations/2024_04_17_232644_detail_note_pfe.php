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
        Schema::create('detail_note_pfe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPfe');
            $table->unsignedBigInteger('idJury');
            $table->double('note1');
            $table->double('note2');
            $table->double('note3');
            $table->double('note4');
            $table->double('note5');
            $table->foreign('idPfe')->references('id')->on('pfes')->onDelete('cascade');
            $table->foreign('idJury')->references('id')->on('profs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

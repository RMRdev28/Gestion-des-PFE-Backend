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
        Schema::create('suivi_pfes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPfe');
            $table->string('pathPfeEssaie');
            $table->double('note')->default(0);
            $table->string('observation')->nullable();
            $table->foreign('idPfe')->references('id')->on('pfes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_pves');
    }
};

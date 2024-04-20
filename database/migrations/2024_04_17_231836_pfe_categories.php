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
        Schema::create('pfe_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPfe');
            $table->unsignedBigInteger('idCategory');
            $table->foreign('idPfe')->references('id')->on('pfes')->onDelete('cascade');
            $table->foreign('idCategory')->references('id')->on('categories')->onDelete('cascade');
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

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
        Schema::create('attachements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idProp');
            $table->string('path');
            $table->string('type');
            $table->double('size');
            $table->foreign('idProp')->references('id')->on('propositions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachements');
    }
};

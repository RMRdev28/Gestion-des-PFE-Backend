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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idSender');
            $table->unsignedBigInteger('idChat');
            $table->tinyInteger('typeMessage');
            $table->string('content');
            $table->foreign('idSender')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idChat')->references('id')->on('chats')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

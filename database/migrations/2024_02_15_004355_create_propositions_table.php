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
        Schema::create('propositions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser');
            $table->string('title');
            $table->text('description');
            $table->enum('type',['entreprise','prof','student']);
            $table->tinyInteger('status')->default(0); // 1 accepted 0 not yet // -1 rejected
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propositions');
    }
};

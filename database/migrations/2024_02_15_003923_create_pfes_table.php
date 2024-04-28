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
        Schema::create('pfes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idBinom');
            $table->unsignedBigInteger('idEns');
            $table->unsignedBigInteger('jury1');
            $table->unsignedBigInteger('jury2');
            $table->string('title');
            $table->string('pfe');
            $table->integer('need_suivis')->default(0);
            $table->enum('level',['l3','m2']);
            $table->enum('status',['valide','termine','revu','pasencore'])->default('pasencore');
            $table->enum('branch',['isil','acad','gtr']);
            $table->text('description');
            $table->integer('year');
            $table->double('note');
            $table->foreign('idBinom')->references('id')->on('binoms')->onDelete('cascade');
            $table->foreign('jury1')->references('id')->on('profs')->onDelete('cascade');
            $table->foreign('jury2')->references('id')->on('profs')->onDelete('cascade');
            $table->foreign('idEns')->references('id')->on('profs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pfes');
    }
};


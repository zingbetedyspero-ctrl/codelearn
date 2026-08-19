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
        Schema::create('tentatives_evaluation', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
        $table->foreignId('evaluation_id')->constrained('evaluations', 'id')->cascadeOnDelete();
        $table->integer('temps_effectuer')->nullable();
        $table->decimal('score', 5, 2)->nullable();
        $table->enum('statut', ['reussi', 'echoue']);
        $table->integer('numero_tentative')->default(1);
        // $table->dateTime('date_tentative');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentatives_evaluation');
    }
};

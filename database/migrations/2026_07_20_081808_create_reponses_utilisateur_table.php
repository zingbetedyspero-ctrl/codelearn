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
        Schema::create('reponses_utilisateur', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('tentative_id')->constrained('tentatives_evaluation', 'id')->cascadeOnDelete();
        $table->foreignId('question_id')->constrained('questions', 'id')->cascadeOnDelete();
        $table->text('reponse')->nullable();
        $table->decimal('note_obtenue', 5, 2)->nullable();
        $table->dateTime('date_reponse');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponses_utilisateur');
    }
};

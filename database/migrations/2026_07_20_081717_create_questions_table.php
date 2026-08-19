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
        Schema::create('questions', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('evaluation_id')->constrained('evaluations', 'id')->cascadeOnDelete();
        $table->text('enonce');
        $table->enum('type_question', ['qcm', 'question']);
        $table->integer('temps_reponse');
        $table->decimal('bareme', 5, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

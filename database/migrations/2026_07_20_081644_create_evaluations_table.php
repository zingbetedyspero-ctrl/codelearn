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
        Schema::create('evaluations', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('cour_id')->constrained('cours', 'id')->cascadeOnDelete();
        $table->foreignId('chapitre_id')->nullable()->constrained('chapitres', 'id')->cascadeOnDelete();
        $table->string('titre');
        $table->enum('type_evaluation', ['test_chapitre', 'examen_final']);
        $table->decimal('seuil_reussite', 5, 2);
        $table->integer('duree_max');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};

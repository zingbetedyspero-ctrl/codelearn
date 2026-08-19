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
        Schema::create('chapitres', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('cour_id')->constrained('cours', 'id')->cascadeOnDelete();// cour et cours pour s'adapter aux conventions de laravel
        $table->string('titre');
        $table->Text('contenu')->nullable();
        $table->integer('ordre_affichage');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapitres');
    }
};

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
        Schema::create('inscriptions_cours', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('payement_id')->constrained('payements', 'id');
        //$table->dateTime('date_inscription')->useCurrent();
        //Nous allons utiliser plutôt le created_at du timestamp
        $table->enum('statut', ['en cours','termine']); //à revoir 
        $table->integer('progression')->nullable(); //identifie le chapitre actuel. Ex:1 ou 2 ou 3...
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions_cours');
    }
};

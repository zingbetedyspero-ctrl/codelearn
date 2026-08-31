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
        Schema::create('certificats', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('inscriptions_cour_id')->constrained('inscriptions_cours', 'id')->cascadeOnDelete();
        // $table->string('numero_certificat')->unique();
        // $table->dateTime('date_obtention');
        $table->decimal('score_final');
        $table->string('fichier_pdf');
        $table->string('code_verification')->unique();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificats');
    }
};

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
        Schema::create('cours', function (Blueprint $table) {
        $table->id('id');
        $table->string('titre');
        $table->text('description')->nullable();
        $table->enum('niveau', ['debutant', 'intermediaire', 'avance'])->default('debutant');
        $table->decimal('prix', 10, 2);
        $table->string('image_couverture')->nullable();
        $table->enum('statut', ['publie', 'non_publie'])->default('non_publie');
        $table->foreignId('user_id')->constrained('users', 'id');
        $table->foreignId('category_id')->nullable()->constrained('categories', 'id');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};

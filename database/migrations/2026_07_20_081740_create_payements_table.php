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
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('cour_id')->constrained('cours', 'id');
            $table->decimal('montant', 10,2);
            $table->string('reference');
            $table->string('transaction_id');
            $table->enum('statut_paiement', ['pending','approved', 'declined', 'canceled', 'refunded', 'transfered']); //à revoir 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payements');
    }
};

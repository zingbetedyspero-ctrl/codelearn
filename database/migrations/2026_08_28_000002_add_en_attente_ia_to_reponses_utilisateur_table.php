<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reponses_utilisateur', function (Blueprint $table) {
            $table->boolean('en_attente_ia')->default(false)->after('note_obtenue');
        });
    }

    public function down(): void
    {
        Schema::table('reponses_utilisateur', function (Blueprint $table) {
            $table->dropColumn('en_attente_ia');
        });
    }
};

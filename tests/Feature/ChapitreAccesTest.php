<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Cour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapitreAccesTest extends TestCase
{
    use RefreshDatabase;

    public function test_chapitre_1_accessible_gratuitement(): void
    {
        $user = User::factory()->create(['role' => 'apprenant', 'statut_compte' => 'actif']);
        $cour = Cour::factory()->create(['statut' => 'publie']);
        $chapitre = $cour->chapitres()->create(['titre' => 'Intro', 'contenu' => '...', 'ordre_affichage' => 1]);

        $response = $this->actingAs($user)->get("/catalogue/{$cour->id}/chapitres/{$chapitre->id}");

        $response->assertOk();
    }

    public function test_chapitre_2_bloque_sans_paiement(): void
    {
        $user = User::factory()->create(['role' => 'apprenant', 'statut_compte' => 'actif']);
        $cour = Cour::factory()->create(['statut' => 'publie']);
        $cour->chapitres()->create(['titre' => 'Intro', 'contenu' => '...', 'ordre_affichage' => 1]);
        $chapitre2 = $cour->chapitres()->create(['titre' => 'Suite', 'contenu' => '...', 'ordre_affichage' => 2]);

        $response = $this->actingAs($user)->get("/catalogue/{$cour->id}/chapitres/{$chapitre2->id}");

        $response->assertForbidden();
    }
}

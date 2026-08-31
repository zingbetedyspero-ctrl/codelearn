<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InscriptionConnexionTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscription_avec_mot_de_passe_faible_echoue(): void
    {
        $response = $this->post('/inscription', [
            'prenom' => 'Test', 'nom' => 'Faible', 'email' => 'faible@test.local',
            'telephone' => '000', 'password' => 'password123', 'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'faible@test.local']);
    }

    public function test_inscription_avec_mot_de_passe_conforme_reussit(): void
    {
        $response = $this->post('/inscription', [
            'prenom' => 'Test', 'nom' => 'Fort', 'email' => 'fort@test.local',
            'telephone' => '000', 'password' => 'MonPass@2026', 'password_confirmation' => 'MonPass@2026',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'fort@test.local', 'role' => 'apprenant']);
    }

    public function test_connexion_avec_compte_inactif_est_bloquee(): void
    {
        $user = User::factory()->create([
            'password' => 'MonPass@2026', 'statut_compte' => 'inactif',
        ]);

        $response = $this->post('/connexion', ['email' => $user->email, 'password' => 'MonPass@2026']);

        $this->assertGuest();
    }
}

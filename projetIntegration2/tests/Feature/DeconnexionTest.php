<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Illuminate\Support\Carbon;


class DeconnexionTest extends TestCase
{

    use DatabaseTransactions;

    #[Test]
    public function la_deconnexion_reinitialise_le_jeton_csrf()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Get the CSRF token before logout
        $this->get('/');
        $tokenBefore = session('_token');
        
        // Perform logout
        $this->post('/deconnexion');
        
        // Make a new request to get a fresh session
        $this->get('/');
        $tokenAfter = session('_token');
        
        // The CSRF token should be different after logout
        $this->assertNotEquals($tokenBefore, $tokenAfter);
    }

    #[Test]
    public function un_utilisateur_peut_se_deconnecter()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->post('/deconnexion');

        // Assert the user is logged out
        $this->assertGuest('web');
        $response->assertRedirect('/connexion'); // Adjust the redirect path as needed
    }

    #[Test]
    public function un_utilisateur_deconnecte_ne_peut_pas_acceder_aux_routes_protegees()
    {
        // First test with an authenticated user to verify the route works
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Access a protected route (replace with your actual protected route)
        $authenticatedResponse = $this->get('/profil');
        $authenticatedResponse->assertStatus(200); // Should be accessible
        
        // Now logout
        $this->post('/deconnexion');
        
        // Try to access the protected route again
        $unauthenticatedResponse = $this->get('/profil');
        
        // Should redirect to login
        $unauthenticatedResponse->assertStatus(302);
        $unauthenticatedResponse->assertRedirect('/connexion');
        
        // Or more specifically if you have a custom middleware message
        // $unauthenticatedResponse->assertSessionHas('error', 'You must be logged in to access this page.');
    }
    #[Test]
    public function la_deconnexion_invalide_la_session()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Verify user is logged in
        $this->assertTrue(auth()->check());
        
        // Store the session ID before logout
        $sessionIdBefore = session()->getId();
        
        // Perform logout
        $response = $this->post('/deconnexion');
        
        // Assert user is logged out
        $this->assertGuest();
        
        // Make a new request and check if the session ID has changed
        $this->get('/');
        $sessionIdAfter = session()->getId();
        
        // Session should be regenerated (different ID)
        $this->assertNotEquals($sessionIdBefore, $sessionIdAfter);
        
        // Verify redirect
        $response->assertRedirect('/connexion');
    }
}

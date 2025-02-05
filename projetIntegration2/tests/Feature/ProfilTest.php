<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Profil;

class ProfilTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = Profil::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt($password = 'adminggg'),
        ]);

        $response = $this->post('/connexion', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // Assert the user is authenticated
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/profil'); // Adjust the redirect path as needed
    }

    /** @test */
    public function user_can_logout()
    {
        $user = Profil::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->post('/deconnexion');

        // Assert the user is logged out
        $this->assertGuest('web');
        $response->assertRedirect('/connexion'); // Adjust the redirect path as needed
    }
}

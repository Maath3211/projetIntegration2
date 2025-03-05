<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt($password = 'adminggg'),
            'email_verified_at' => Carbon::now(),
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
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->post('/deconnexion');

        // Assert the user is logged out
        $this->assertGuest('web');
        $response->assertRedirect('/connexion'); // Adjust the redirect path as needed
    }
}

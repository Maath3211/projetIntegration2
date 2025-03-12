<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;


class ConnexionTest extends TestCase
{

    use DatabaseTransactions;

    #[Test]
    public function un_utilisateur_peut_se_connecter_avec_des_informations_valides()
    {
        $user = User::factory()->create([
            'email' => 'test165985123@test.com',
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

    #[Test]
    public function un_utilisateur_ne_peut_pas_se_connecter_avec_des_informations_invalides()
    {
        $user = User::factory()->create([
            'email' => 'test165985123@test.com',
            'password' => bcrypt('adminggg'),
            'email_verified_at' => Carbon::now(),
        ]);

        $response = $this->post('/connexion', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // Assert the user is not authenticated
        $this->assertGuest();
        $response->assertSessionHas('errors');
        $response->assertRedirect('/'); // Verify it redirects back
    }

    #[Test]
    public function un_utilisateur_ne_peut_pas_se_connecter_avec_un_compte_non_verifie()
    {
        $user = User::factory()->create([
            'email' => 'test165985123@test.com',
            'password' => bcrypt('adminggg'),
        ]);

        $response = $this->post('/connexion', [
            'email' => $user->email,
            'password' => bcrypt('adminggg'),
        ]);

        // Assert the user is not authenticated
        $this->assertGuest();
        $response->assertSessionHas('errors');
        $response->assertRedirect('/'); // Verify it redirects back
    }

    
}

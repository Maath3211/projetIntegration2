<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

class CreationCompteTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function utilisateur_peut_creer_un_compte_avec_donnees_valides(): void
    {
        // Mock notifications/emails if any are sent during registration
        Notification::fake();
        
        // Generate unique data for registration
        $email = 'test_' . time() . '@example.com';
        $password = 'Password123!';
        
        $response = $this->post('/creerCompte', [
            'prenom' => 'Test',
            'nom' => 'Utilisateur',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
            // Add any other required fields your registration form has
        ]);
        
        // Assert redirect to expected page after registration
        $response->assertStatus(302); 
        $response->assertRedirect('/connexion'); // Adjust to your actual redirect path
        
        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
        
        // Verify user can login with these credentials
        $this->assertTrue(auth()->attempt([
            'email' => $email,
            'password' => $password
        ]));
    }
    
    #[Test]
    public function creation_compte_echoue_avec_email_existant(): void
    {
        // Create a user first
        $existingUser = User::factory()->create();
        
        // Try to register with same email
        $response = $this->post('/creerCompte', [
            'prenom' => 'Another',
            'nom' => 'User',
            'email' => $existingUser->email, // Using existing email
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
        ]);
        
        // Should fail with validation error
        $response->assertStatus(302);
        $response->assertSessionHas('errors');
        
        // No new user should be created
        $this->assertEquals(1, User::where('email', $existingUser->email)->count());
    }
    
    #[Test]
    public function creation_compte_echoue_avec_mot_de_passe_faible(): void
    {
        $weakPasswords = [
            'short', // Too short
            '12345678', // No letters
            'password', // Common password
        ];
        
        foreach ($weakPasswords as $index => $password) {
            // Use a unique email for each attempt to avoid conflicts
            $email = 'weak_password_test_' . $index . '@example.com';
            
            $response = $this->post('/creerCompte', [
                'prenom' => 'Test',
                'nom' => 'User',
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'pays' => 'France',
                'genre' => 'Homme',
                'dateNaissance' => '1990-01-01',
            ]);
            
            // Should return a redirect
            $response->assertStatus(302);
            
            // Check that the application rejected the weak password
            // by verifying the user wasn't created
            $this->assertDatabaseMissing('users', [
                'email' => $email,
            ]);
            
            // Instead of checking session values directly, check for 
            // either session errors or a redirect back (which implies validation failed)
            $this->assertTrue(
                $response->isRedirect() && 
                (session()->has('errors') || session()->has('message') || session()->has('error')),
                "Expected validation failure for password: $password"
            );
        }
    }
    
    #[Test]
    public function creation_compte_echoue_avec_champs_manquants(): void
    {
        // Test with missing email
        $response = $this->post('/creerCompte', [
            'prenom' => 'Test',
            'nom' => 'User',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
        ]);
        
        $response->assertStatus(302);
        $response->assertSessionHas('errors');
        
        // Test with missing password
        $response = $this->post('/creerCompte', [
            'prenom' => 'Test',
            'nom' => 'User',
            'email' => 'missing_password@example.com',
        ]);
        
        $response->assertStatus(302);
        $response->assertSessionHas('errors');
    }

    #[Test]
    public function creation_compte_echoue_avec_date_naissance_trop_ancienne(): void
    {
        // Test date before 1900
        $invalidDate = '1899-12-31';
        $email = 'too_old_date@example.com';
        
        $response = $this->post('/creerCompte', [
            'prenom' => 'Test',
            'nom' => 'User',
            'email' => $email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => $invalidDate,
        ]);
        
        // Should redirect with validation errors
        $response->assertStatus(302);
        
        // User should not be created
        $this->assertDatabaseMissing('users', [
            'email' => $email,
        ]);
        
        // Check for validation errors
        $this->assertTrue(
            $response->isRedirect() && 
            (session()->has('errors') || session()->has('message') || session()->has('error')),
            "Expected validation failure for birth date: $invalidDate"
        );
    }
    
    #[Test]
    public function creation_compte_echoue_avec_date_naissance_future(): void
    {
        // Test future dates
        $futureDates = [
            Carbon::tomorrow()->format('Y-m-d'), // Tomorrow
            '2100-01-01', // Far future
        ];
        
        foreach ($futureDates as $index => $invalidDate) {
            $email = 'future_date_test_' . $index . '@example.com';
            
            $response = $this->post('/creerCompte', [
                'prenom' => 'Test',
                'nom' => 'User',
                'email' => $email,
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'pays' => 'France',
                'genre' => 'Homme',
                'dateNaissance' => $invalidDate,
            ]);
            
            // Should redirect with validation errors
            $response->assertStatus(302);
            
            // User should not be created
            $this->assertDatabaseMissing('users', [
                'email' => $email,
            ]);
            
            // Check for validation errors
            $this->assertTrue(
                $response->isRedirect() && 
                (session()->has('errors') || session()->has('message') || session()->has('error')),
                "Expected validation failure for future birth date: $invalidDate"
            );
        }
    }
    
    #[Test]
    public function utilisateur_peut_creer_compte_avec_date_naissance_valide(): void
    {
        // Valid dates between 1900-01-01 and today
        $validDates = [
            '1900-01-01', // Minimum valid date
            '1950-06-15', // Mid-century date
            Carbon::now()->subYears(18)->format('Y-m-d'), // 18 years ago
            Carbon::yesterday()->format('Y-m-d'), // Yesterday
        ];
        
        foreach ($validDates as $index => $validDate) {
            // Use unique email for each test case
            $email = 'valid_date_test_' . $index . '@example.com';
            $password = 'Password123!';
            
            // Mock notifications if needed
            Notification::fake();
            
            // Attempt registration with valid date
            $response = $this->post('/creerCompte', [
                'prenom' => 'Test',
                'nom' => 'User',
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'pays' => 'France',
                'genre' => 'Homme',
                'dateNaissance' => $validDate,
            ]);
            
            // Should be successful redirect
            $response->assertStatus(302);
            
            // No errors for date
            $this->assertFalse(
                session()->has('errors.dateNaissance'),
                "Date validation failed unexpectedly for valid date: $validDate"
            );
            
            // User should be created with correct date
            $this->assertDatabaseHas('users', [
                'email' => $email,
                'dateNaissance' => $validDate,
            ]);
            
            // Should be able to login
            $this->assertTrue(auth()->attempt([
                'email' => $email,
                'password' => $password
            ]));
        }
    }
}

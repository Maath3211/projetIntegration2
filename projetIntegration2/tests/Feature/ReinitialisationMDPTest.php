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

class ReinitialisationMDPTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function utilisateur_peut_demander_lien_reinitialisation_mot_de_passe(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Mock notifications to avoid actually sending emails
        Notification::fake();
        
        // Send the password reset request
        $response = $this->post('/reinitialisation', [
            'email' => $user->email
        ]);
        
        // Check that the request was successful
        $response->assertStatus(302); // Redirects somewhere
        $response->assertSessionHas('message'); // Should have a success message
        
        // Verify a token was created in the password_resets table
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    #[Test]
    public function utilisateur_ne_peut_pas_demander_reinitialisation_pour_email_inexistant(): void
    {
        // Send the password reset request with a non-existent email
        $response = $this->post('/reinitialisation', [
            'email' => 'nonexistent@example.com'
        ]);
        
        // Adjust expectation based on how your app actually handles this
        $response->assertStatus(302);
        
        // If your app shows a general message instead of a specific error
        $response->assertSessionHas('message'); 
        // OR maybe it has a different error key
        // $response->assertSessionHasErrors(['invalidEmail']);
        
        // No token should be created
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'nonexistent@example.com',
        ]);
    }

    #[Test]
    public function lien_reinitialisation_est_envoye_a_la_bonne_adresse_email(): void
    {
        // Create two users to verify email is sent to the correct one
        $userRequesting = User::factory()->create(['email' => 'requesting@example.com']);
        $otherUser = User::factory()->create(['email' => 'other@example.com']);
        
        // Mock the mail system instead of notifications
        \Illuminate\Support\Facades\Mail::fake();
        
        // Send the password reset request
        $response = $this->post('/reinitialisation', [
            'email' => $userRequesting->email
        ]);
        
        // Check that the request was successful
        $response->assertStatus(302);
        $response->assertSessionHas('message');
        
        // Assert mail was sent to the correct user
        \Illuminate\Support\Facades\Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) use ($userRequesting) {
            return $mail->hasTo($userRequesting->email);
        });
        
        // Assert mail was NOT sent to the other user
        \Illuminate\Support\Facades\Mail::assertNotSent(function (\Illuminate\Mail\Mailable $mail) use ($otherUser) {
            return $mail->hasTo($otherUser->email);
        });
    }

    #[Test]
    public function utilisateur_peut_reinitialiser_mot_de_passe_avec_jeton_valide(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a password reset token directly in the database
        $token = \Illuminate\Support\Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token, // Laravel hashes the token in the DB
            'created_at' => Carbon::now()
        ]);
        
        // New password to set
        $newPassword = 'new-Password123';
        
        // Submit the password reset form with the token as form data
        $response = $this->post("/reinitialisationMDP", [
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
            'token' => $token, // Include token as form field
        ]);
        
        // Check redirect and success message
        $response->assertStatus(302);
        $response->assertSessionHas('message'); // Success message
        
        // Token should be removed after successful reset
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email
        ]);
        
        // User should be able to login with new password
        $this->assertTrue(auth()->attempt([
            'email' => $user->email,
            'password' => $newPassword
        ]));
    }

    #[Test]
    public function utilisateur_ne_peut_pas_reinitialiser_mot_de_passe_avec_jeton_invalide(): void
    {
        // Create a user
        $user = User::factory()->create();
        $originalPassword = 'original-password123';
        $user->password = bcrypt($originalPassword);
        $user->save();
        
        // New password to set
        $newPassword = 'new-password123';
        
        // Submit reset with invalid token
        $response = $this->post('/reinitialisationMDP', [
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
            'token' => 'invalid-token', // Include invalid token as form field
        ]);
        
        // Should show error
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        
        // User should NOT be able to login with new password
        $this->assertFalse(auth()->attempt([
            'email' => $user->email,
            'password' => $newPassword
        ]));
        
        // But should still be able to login with original password
        $this->assertTrue(auth()->attempt([
            'email' => $user->email,
            'password' => $originalPassword
        ]));
    }

    #[Test]
    public function utilisateur_ne_peut_pas_reinitialiser_mdp_avec_mot_de_passe_faible(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a valid password reset token
        $token = \Illuminate\Support\Str::random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        
        // Original password to compare later
        $originalPassword = 'original-password123';
        $user->password = bcrypt($originalPassword);
        $user->save();
        
        // Create weak passwords to test validation
        $weakPasswords = [
            'abc', // Too short (less than 8 characters)
            '1234567', // Too short (exactly 7 characters)
        ];
        
        foreach ($weakPasswords as $weakPassword) {
            // Submit reset with weak password
            $response = $this->post('/reinitialisationMDP', [
                'email' => $user->email,
                'password' => $weakPassword,
                'password_confirmation' => $weakPassword,
                'token' => $token,
            ]);
            
            // Should show validation error for password
            $response->assertStatus(302);
            $response->assertSessionHasErrors('password');
            
            // Token should still exist in database (not consumed)
            $this->assertDatabaseHas('password_reset_tokens', [
                'email' => $user->email,
            ]);
            
            // User should still be able to login with original password
            $this->assertTrue(auth()->attempt([
                'email' => $user->email,
                'password' => $originalPassword
            ]));
            
            // User should NOT be able to login with weak password
            $this->assertFalse(auth()->attempt([
                'email' => $user->email,
                'password' => $weakPassword
            ]));
        }
    }
}

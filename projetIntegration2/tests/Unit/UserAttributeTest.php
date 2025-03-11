<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Carbon;

class UserAttributeTest extends TestCase
{
    use DatabaseTransactions;
    
    #[Test]
    public function les_attributs_fillable_peuvent_etre_definis_par_mass_assignment()
    {
        // Data for all fillable attributes
        $userData = [
            'email' => 'test@example.com',
            'prenom' => 'John',
            'nom' => 'Doe',
            'imageProfil' => 'profile.jpg',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
            'codeVerification' => '123456',
            'aPropos' => 'About me text',
            'google_id' => '12345678',
            'password' => 'password123', // Add this required field
        ];
        
        // Create user with mass assignment
        $user = User::create($userData);
        
        // Verify each fillable attribute is set correctly
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['prenom'], $user->prenom);
        $this->assertEquals($userData['nom'], $user->nom);
        $this->assertEquals($userData['imageProfil'], $user->imageProfil);
        $this->assertEquals($userData['pays'], $user->pays);
        $this->assertEquals($userData['genre'], $user->genre);
        $this->assertEquals($userData['dateNaissance'], $user->dateNaissance);
        $this->assertEquals($userData['codeVerification'], $user->codeVerification);
        $this->assertEquals($userData['aPropos'], $user->aPropos);
        $this->assertEquals($userData['google_id'], $user->google_id);
        // Don't check password as it will be hashed
    }
    
    #[Test]
    public function les_attributs_non_fillable_sont_proteges()
    {
        // Create a user with a non-fillable attribute
        $userData = [
            'email' => 'test@example.com',
            'prenom' => 'John',
            'nom' => 'Doe',
            'imageProfil' => 'profile.jpg',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
            'password' => 'password123',
            'created_at' => '2020-01-01 00:00:00', // This is not in $fillable
            'is_admin' => true, // This is not in $fillable
        ];
        
        $user = User::create($userData);
        
        // Fillable attributes should be set
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['prenom'], $user->prenom);
        $this->assertEquals($userData['nom'], $user->nom);
        
        // Non-fillable attributes should not be set
        $this->assertNotEquals('2020-01-01 00:00:00', $user->created_at);
        $this->assertNull($user->is_admin);
    }
    
    #[Test]
    public function les_attributs_caches_sont_bien_caches()
    {
        // Create user with password
        $user = User::create([
            'email' => 'test@example.com',
            'prenom' => 'John',
            'nom' => 'Doe',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
            'password' => bcrypt('password123'),
        ]);
        
        // Convert to array
        $userArray = $user->toArray();
        
        // Hidden attributes should not be in the array
        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }
    
    #[Test]
    public function les_attributs_sont_bien_casts()
    {
        // Create user with email_verified_at
        $user = User::create([
            'email' => 'test@example.com',
            'prenom' => 'John',
            'nom' => 'Doe',
            'pays' => 'France',
            'genre' => 'Homme',
            'dateNaissance' => '1990-01-01',
            'password' => bcrypt('password123'),
        ]);
        
        $user->email_verified_at = Carbon::now();
        $user->save();

        
        // Check that email_verified_at is a datetime object
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
        
        // For hashed attributes, we can't check the exact value but we can verify it's not stored as plaintext
        $this->assertNotEquals('password123', $user->password);
    }
}

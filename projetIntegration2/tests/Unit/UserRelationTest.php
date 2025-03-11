<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class UserRelationTest extends TestCase
{
    use DatabaseTransactions;
    
    #[Test]
    public function relation_clans_retourne_donnees_correctes(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create some clans using factory or direct creation
        $clan1 = \App\Models\Clan::create([
            'nom' => 'Clan Test 1',
            'description' => 'Description du clan 1',
            'adminId' => $user->id,
        ]);
        
        $clan2 = \App\Models\Clan::create([
            'nom' => 'Clan Test 2',
            'description' => 'Description du clan 2',
            'adminId' => $user->id,
        ]);
        

        
        // If you have a simple many-to-many relationship:
        $user->clans()->attach([$clan1->id, $clan2->id]);
        
        
        // Refresh the user model to ensure relationships are loaded
        $user = $user->fresh();
        
        // Assert the relationship works correctly
        $this->assertTrue($user->clans->contains($clan1));
        $this->assertTrue($user->clans->contains($clan2));
        $this->assertEquals(2, $user->clans->count());
        
        // Test the inverse relationship if applicable
        $this->assertTrue($clan1->utilisateurs->contains($user));
        $this->assertTrue($clan2->utilisateurs->contains($user));
        
        // Verify that a user without clans returns an empty collection
        $userWithoutClans = User::factory()->create();
        $this->assertCount(0, $userWithoutClans->clans);
    }
}

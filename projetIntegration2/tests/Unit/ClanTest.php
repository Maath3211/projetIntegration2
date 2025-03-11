<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Clan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClanTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_a_many_to_many_relationship_with_users()
    {
        // Create a clan and some users
        $clan = Clan::factory()->create();
        $users = User::factory()->count(3)->create();

        // Attach users to the clan
        $clan->utilisateurs()->attach($users->pluck('id'));

        // Assert the relationship
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $clan->utilisateurs);
        $this->assertCount(3, $clan->utilisateurs);
        $this->assertTrue($clan->utilisateurs->contains($users->first()));
    }

    #[Test]
    public function it_has_the_correct_fillable_attributes()
    {
        $clan = new Clan();

        $this->assertEquals([
            'adminId',
            'image',
            'nom',
            'public',
            'id'
        ], $clan->getFillable());
    }
}

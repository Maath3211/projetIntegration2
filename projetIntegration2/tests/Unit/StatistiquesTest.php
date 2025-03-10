<?php
namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Statistiques;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatistiquesTest extends TestCase
{
    use DatabaseTransactions;
    #[Test]
    public function test_statistiques_creation(): void
    {
        $user = User::factory()->create();

        $statistique = Statistiques::factory()->create([
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'date' => now(),
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('statistiques', [
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'user_id' => $user->id,
        ]);
    }


    #[Test]
    public function test_statistiques_belongs_to_user(): void
    {
        $user = User::factory()->create();

        $statistique = Statistiques::factory()->create([
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'date' => now(),
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $statistique->user);
        $this->assertEquals($user->id, $statistique->user->id);
    }

    #[Test]
    public function test_statistiques_modifier(): void
    {
        $user = User::factory()->create();

        $statistique = Statistiques::factory()->create([
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'date' => now(),
            'user_id' => $user->id,
        ]);

        $statistique->update([
            'score' => 200,
        ]);

        $this->assertDatabaseHas('statistiques', [
            'nomStatistique' => 'Test Stat',
            'score' => 200,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function test_statistiques_supprimer(): void
    {
        $user = User::factory()->create();

        $statistique = Statistiques::factory()->create([
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'date' => now(),
            'user_id' => $user->id,
        ]);

        $statistique->delete();

        $this->assertDatabaseMissing('statistiques', [
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function test_statistiques_fillable(): void
    {
        $user = User::factory()->create();

        $statistique = new Statistiques([
            'nomStatistique' => 'Test Stat',
            'score' => 100,
            'date' => now(),
            'user_id' => $user->id,
        ]);

        $this->assertEquals('Test Stat', $statistique->nomStatistique);
        $this->assertEquals(100, $statistique->score);
        $this->assertEquals($user->id, $statistique->user_id);
    }
}
?>

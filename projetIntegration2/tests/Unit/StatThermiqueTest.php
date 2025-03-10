<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\StatThermique;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class StatThermiqueTest extends TestCase
{

use DatabaseTransactions;

    #[Test]
    public function test_statThermique_creation()
    {
        $user = User::factory()->create();
        $statThermique = StatThermique::factory()->create([
            'user_id' => $user->id,
            'date' => now(),
            'type_activite' => 1 
        ]);

        $this->assertDatabaseHas('statThermique', [
            'id' => $statThermique->id,
            'user_id' => $user->id,
            'type_activite' => 1
        ]);
    }

    #[Test]
    public function test_statThermique_modifier()
    {
        $statThermique = StatThermique::factory()->create();
        $statThermique->update(['type_activite' => 2]); 

        $this->assertDatabaseHas('statThermique', [
            'id' => $statThermique->id,
            'type_activite' => 2
        ]);
    }

    #[Test]
    public function test_statThermique_supprimer()
    {
        $statThermique = StatThermique::factory()->create();
        $statThermique->delete();

        $this->assertDatabaseMissing('statThermique', [
            'id' => $statThermique->id
        ]);
    }
    #[Test]
    public function test_statThermique_fillable()
    {
        $statThermique = new StatThermique();

        $fillable = ['date', 'type_activite', 'user_id'];
        $this->assertEquals($fillable, $statThermique->getFillable());
    }
}

<?php

namespace Tests\Unit;

use App\Models\Score;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ScoreTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function peut_creer_un_score()
    {
        $score = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-15',
            'score' => 150
        ]);
        
        $this->assertInstanceOf(Score::class, $score);
        $this->assertEquals(150, $score->score);
        $this->assertEquals('2023-10-15', $score->date);
        $this->assertEquals($this->user->id, $score->user_id);
    }

    #[Test]
    public function peut_creer_score_avec_date_actuelle()
    {
        $aujourdhui = Carbon::now()->format('Y-m-d');
        
        $score = Score::create([
            'user_id' => $this->user->id,
            'date' => $aujourdhui,
            'score' => 200
        ]);
        
        $this->assertEquals($aujourdhui, $score->date);
    }

    #[Test]
    public function peut_retrouver_score_depuis_utilisateur()
    {
        // Créer plusieurs scores pour l'utilisateur
        $score1 = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-10',
            'score' => 100
        ]);
        
        $score2 = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-15',
            'score' => 150
        ]);
        
        // Vérifier que les scores sont bien liés à l'utilisateur
        $userScores = $this->user->scores;
        
        $this->assertCount(2, $userScores);
        $this->assertTrue($userScores->contains('id', $score1->id));
        $this->assertTrue($userScores->contains('id', $score2->id));
    }

    #[Test]
    public function peut_mettre_a_jour_un_score()
    {
        $score = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-15',
            'score' => 150
        ]);
        
        $score->score = 200;
        $score->save();
        
        $updatedScore = Score::find($score->id);
        $this->assertEquals(200, $updatedScore->score);
    }

    #[Test]
    public function peut_supprimer_un_score()
    {
        $score = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-15',
            'score' => 150
        ]);
        
        $scoreId = $score->id;
        $score->delete();
        
        $this->assertNull(Score::find($scoreId));
    }

    #[Test]
    public function la_relation_utilisateur_fonctionne_correctement()
    {
        $score = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-15',
            'score' => 150
        ]);
        
        $this->assertInstanceOf(User::class, $score->user);
        $this->assertEquals($this->user->id, $score->user->id);
    }

    #[Test]
    public function peut_filtrer_scores_par_date()
    {
        // Créer des scores à différentes dates
        $score1 = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-01-15',
            'score' => 100
        ]);
        
        $score2 = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-05-15',
            'score' => 150
        ]);
        
        $score3 = Score::create([
            'user_id' => $this->user->id,
            'date' => '2023-10-15',
            'score' => 200
        ]);
        
        // Filtrer les scores après une certaine date
        $scoresFiltres = Score::where('date', '>=', '2023-05-01')
            ->where('user_id', $this->user->id)
            ->get();
        
        $this->assertCount(2, $scoresFiltres);
        $this->assertTrue($scoresFiltres->contains('id', $score2->id));
        $this->assertTrue($scoresFiltres->contains('id', $score3->id));
        $this->assertFalse($scoresFiltres->contains('id', $score1->id));
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreExercice extends Model
{
    use HasFactory;
    protected $table = 'score_exercice';
    protected $fillable = [
        'statistique_id',
        'semaine',
        'score',
    ];

    public function statistique()
    {
        return $this->belongsTo(Statistiques::class);
    }
}

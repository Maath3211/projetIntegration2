<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistique extends Model
{
    use HasFactory;

    protected $table = "statistique";

    protected $fillable = [
        'statistiqueId',
        'nomStatistique',
        'score',
        'date',
        'user_id'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

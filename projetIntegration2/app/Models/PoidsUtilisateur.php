<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoidsUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'poids_utilisateur';
    protected $fillable = ['semaine', 'poids','user_id'];
    public $incrementing = true; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

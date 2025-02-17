<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoidsUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'poids_utilisateur'; // Nom de la table
    protected $fillable = ['semaine', 'poids','user_id']; // Champs remplissables
    public $incrementing = false;
    protected $primaryKey = ['semaine']; 
}

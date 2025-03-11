<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Canal extends Model
{
    use HasFactory;
    
    protected $table = "canals";
    protected $fillable = [
        'titre',
        'clanId',
        'categorieId',
    ];

    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;

    public function clan(){
        return $this->belongsTo(Clan::class, 'clanId');
    }

    public function categorie(){
        return $this->belongsTo(CategorieCanal::class, 'categorieId');
    }
}

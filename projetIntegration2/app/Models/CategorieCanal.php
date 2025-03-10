<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieCanal extends Model
{
    use HasFactory;
    
    protected $table = "categories_canal";
    protected $fillable = [
        'categorie',
        'clanId',
    ];

    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;

    public function clan(){
        return $this->belongsTo('App\Models\Clan', 'clanId');
    }
}

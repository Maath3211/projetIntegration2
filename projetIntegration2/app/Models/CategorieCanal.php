<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieCanal extends Model
{
    protected $table = "categories_canal";
    protected $fillable = [
        'categorie',
        'clanId',
    ];

    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;
}

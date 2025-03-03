<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canal extends Model
{
    protected $table = "canals";
    protected $fillable = [
        'titre',
        'clanId',
        'categorieId',
    ];

    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;
}

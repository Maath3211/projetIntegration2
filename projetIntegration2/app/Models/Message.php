<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "utilisateur_ami";
    protected $fillable = [
        'message',
        'envoyeur_id',
        'receveur_id',
        'created_at',
    ];

    public $timestamps = false;

    protected $dates = ['created_at', 'read_at']; 


}

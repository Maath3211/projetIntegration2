<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    protected $table = "clans";

    protected $fillable = [
        'adminId',
        'image',
        'nom',
        'public',
        'id'
    ];

    public function utilisateurs(){
        return $this->belongsToMany(User::class, 'clan_users', 'clan_id', 'user_id');
    }
}

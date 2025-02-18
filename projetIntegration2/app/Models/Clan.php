<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    protected $table = "clans"; // Ensure this matches the actual table name

    protected $fillable = [
        'adminId',
        'image',
        'nom',
        'public',
        'id'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'clan_users', 'clan_id', 'user_id');
    }
}

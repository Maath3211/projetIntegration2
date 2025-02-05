<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    use HasFactory;

    protected $table = "clan";

    protected $fillable = [
        'adminId',
        'image',
        'nom',
        'public'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'Clan_user', 'clan_id', 'user_id');
    }
}

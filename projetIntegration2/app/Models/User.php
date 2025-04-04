<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";

    protected $fillable = [
        'email',
        'prenom',
        'nom',
        'imageProfil',
        'pays',
        'genre',
        'dateNaissance',
        'codeVerification',
        'aPropos',
        'id',
        'google_id',
        'password',
    ];

    protected $hidden = [

        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

    ];

    public function clans()
    {
        return $this->belongsToMany(Clan::class, 'clan_users');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}

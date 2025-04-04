<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Clan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilisateurClan extends Model
{
    use HasFactory;

    protected $table = "conversation_clan";
    protected $fillable = [
        'idEnvoyer',
        'idClan',
        'idCanal',
        'message',
        'fichier',
        'created_at',
    ];

    //Laravel les creer en englais
    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;


    //Obliger d'être en englais
    /**
     * Get the user that owns the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idEnvoyer');
    }

    /**
     * Get the group that owns the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clan(): BelongsTo
    {
        return $this->belongsTo(Clan::class, 'idClan');
    }


}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Message extends Model
{
    use HasFactory;

    protected $table = "conversation_ami";
    protected $fillable = [
        'message',
        'idEnvoyer',
        'idReceveur',
        'fichier',
        'created_at',
    ];

    //obliger en englais
    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;


    //Obliger d'être en anglais
/**
 * Get the from that owns the Message
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idEnvoyer');
    }

    


}

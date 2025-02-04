<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Message extends Model
{
    protected $table = "utilisateur_ami";
    protected $fillable = [
        'message',
        'envoyeur_id',
        'receveur_id',
        'created_at',
    ];

    protected $dates = ['created_at', 'read_at']; 

    public $timestamps = false;


/**
 * Get the from that owns the Message
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receveur_id');
    }

    


}

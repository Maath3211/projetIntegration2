<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Clan_user extends Pivot
{
    protected $table = 'clan_user';

    protected $fillable = [
        'clan_id',
        'user_id'
    ];
}

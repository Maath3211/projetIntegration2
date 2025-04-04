<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = "scores";
    protected $fillable = [
        'user_id',
        'date',
        'score'

    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}

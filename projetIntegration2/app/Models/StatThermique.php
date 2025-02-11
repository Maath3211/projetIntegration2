<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatThermique extends Model
{
    use HasFactory;
    protected $table = 'statThermique';
    protected $fillable = ['date', 'type_activite'];
}

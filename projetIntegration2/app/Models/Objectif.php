<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;
    protected $table = 'objectifs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['id','titre', 'description', 'completer', 'user_id'];
}

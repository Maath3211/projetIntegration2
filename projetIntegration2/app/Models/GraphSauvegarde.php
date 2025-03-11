<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GraphSauvegarde extends Model
{
    use HasFactory;

    protected $table = 'graph_sauvegardes';

    protected $fillable = [
        'user_id',
        'type',
        'clan_id',
        'titre',
        'date_debut',
        'date_fin',
        'data',
        'date_expiration',
    ];

    protected $casts = [
        'data' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_expiration' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->date_expiration) {
                $model->date_expiration = Carbon::now()->addDays(90);
            }
        });
    }
}

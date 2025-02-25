<?php
// app/Models/WeeklyScore.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyScore extends Model
{
    protected $fillable = [
        'scoreable_type',
        'scoreable_id',
        'score',
        'week_start'
    ];

    protected $casts = [
        'week_start' => 'date'
    ];

    public function scoreable()
    {
        return $this->morphTo();
    }
}
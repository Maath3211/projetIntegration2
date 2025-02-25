<?php
// app/Console/Commands/TrackWeeklyScores.php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clan;
use App\Models\User;
use App\Models\WeeklyScore;
use Carbon\Carbon;

class TrackWeeklyScores extends Command
{
    protected $signature = 'scores:track-weekly';

    public function handle()
    {
        $weekStart = Carbon::now()->startOfWeek();
        
        // Track clan scores
        Clan::all()->each(function ($clan) use ($weekStart) {
            WeeklyScore::create([
                'scoreable_type' => Clan::class,
                'scoreable_id' => $clan->id,
                'score' => $clan->total_score,
                'week_start' => $weekStart
            ]);
        });

        // Track user scores
        User::all()->each(function ($user) use ($weekStart) {
            WeeklyScore::create([
                'scoreable_type' => User::class,
                'scoreable_id' => $user->id,
                'score' => $user->total_score,
                'week_start' => $weekStart
            ]);
        });
    }
}
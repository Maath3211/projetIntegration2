<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Livewire::component('global-leaderboard', \App\Http\Livewire\GlobalLeaderboard::class);
        Livewire::component('clan-leaderboard', \App\Http\Livewire\ClanLeaderboard::class);
        Livewire::component('test-component', \App\Http\Livewire\TestComponent::class);
        Livewire::component('leaderboard-switcher', \App\Http\Livewire\LeaderboardSwitcher::class);
        Livewire::component('sidebar-clans', \App\Http\Livewire\SidebarClans::class);
        Livewire::component('score-graph', \App\Http\Livewire\ScoreGraph::class);
        
        //Schema::defaultStringLength(191);
    
        /*
        if ($this->app->environment() === 'local') {
            DB::listen(function (QueryExecuted $query) {
                file_put_contents(
                    'php://stdout',
                    "\e[34m{$query->sql}\t\e[37m" . json_encode($query->bindings) . "\t\e[32m{$query->time}ms\e[0m\n"
                );
            });

        }*/
    }
    
}

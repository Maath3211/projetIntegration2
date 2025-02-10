<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;


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
    public function boot(): void
    {
        Livewire::component('global-leaderboard', \App\Http\Livewire\GlobalLeaderboard::class);
        Livewire::component('clan-leaderboard', \App\Http\Livewire\ClanLeaderboard::class);
        Livewire::component('test-component', \App\Http\Livewire\TestComponent::class);
    }
}

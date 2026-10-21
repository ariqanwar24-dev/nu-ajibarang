<?php

namespace App\Providers;

use App\Models\Berita;
use App\Models\Pengurus;
use App\Models\User;
use App\Policies\BeritaPolicy;
use App\Policies\PengurusPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(Berita::class, BeritaPolicy::class);
        Gate::policy(Pengurus::class, PengurusPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}

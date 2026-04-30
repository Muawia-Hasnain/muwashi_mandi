<?php

namespace App\Providers;

use App\Models\Ad;
use App\Policies\AdPolicy;
use Illuminate\Pagination\Paginator;
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
        // Register Ad policy
        Gate::policy(Ad::class, AdPolicy::class);

        // Use simple Bootstrap-compatible pagination (or default Tailwind)
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::simple-default');
    }
}

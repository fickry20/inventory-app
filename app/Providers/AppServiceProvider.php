<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\NotifikasiRop;

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
        Paginator::useBootstrapFour();

        // Share ROP alerts to all views globally (useful for sidebar and topbar)
        View::composer('*', function ($view) {
            $activeRopAlertsCount = NotifikasiRop::where('rop_sudah_ditangani', false)->count();
            $activeRopAlerts = NotifikasiRop::with('sukuCadang')
                ->where('rop_sudah_ditangani', false)
                ->latest('rop_created_at')
                ->take(5)
                ->get();
            $view->with(compact('activeRopAlertsCount', 'activeRopAlerts'));
        });
    }
}

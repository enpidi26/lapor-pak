<?php

namespace App\Providers;

use App\Models\Laporan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
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
        Carbon::setLocale(config('app.locale'));

        // Jumlah antrean untuk lencana di navigasi pengurus.
        View::composer('layouts.app', function ($view) {
            $view->with('jumlahMenunggu', auth()->check() && auth()->user()->isAdmin()
                ? Laporan::where('status', 'pending')->count()
                : 0);
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Category;
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
        View::composer(['partials.navbar', 'partials.footer', 'events.index'], function ($view): void {
            $view->with('footerCategories', Category::query()
                ->whereNotNull('name')
                ->orderBy('name')
                ->limit(6)
                ->get());
        });
    }
}

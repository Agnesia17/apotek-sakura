<?php

namespace App\Providers;

use App\Models\Cart;
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
        View::composer('*', function ($view) {
            $cartCount = 0;

            if (session('customer_logged_in') && session('customer_id')) {
                $cartCount = Cart::getItemCountByPelanggan(session('customer_id'));
            }

            $view->with('cartCount', $cartCount);
        });
    }
}

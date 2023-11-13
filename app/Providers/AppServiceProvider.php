<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravel\Passport\Passport;
use View;
use App\Models\Menu;
use App\Models\MenuItem;
use Cache;
use Session;
use Illuminate\Support\Facades\Crypt;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $eval = md5('tamphat.edu.vn' . env('APP_URL') . md5('tamphat.edu.vn-0904720388'));
        // if ($eval !== env('APP_TOKEN')) {
        //     die;
        // }
        Paginator::useBootstrap();
        Passport::routes();
        View::composer(['homepage.*', 'cart.*', 'product.*'], function ($view) {
            $cart = [];
            $cart['cart'] = Session::get('cart');
            $total = $quantity = 0;
            if (isset($cart['cart']) && is_array($cart['cart']) && count($cart['cart']) > 0) {
                foreach ($cart['cart'] as $k => $item) {
                    $total += $item['quantity'] * $item['price'];
                    $quantity += $item['quantity'];
                }
            }
            $cart['total'] = $total;
            $cart['quantity'] = $quantity;
            $view->with('cart', $cart);
        });
    }
}

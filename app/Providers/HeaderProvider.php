<?php

namespace App\Providers;

use App\Models\Site\Client;
use App\Models\Site\Seller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class HeaderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * Always show User's data in all pages' <header>
     */
    public function boot(): void
    {
        View::composer('layouts.site-nav', function ($view) {
            // $userName = null;
            // if (Session::get('id_seller')) {
            //     $idUser = Session::get('id_seller');
            //     $userName = Seller::find($idUser)->name;
            // } elseif (Session::get('id_client')) {
            //     $idUser = Session::get('id_client');
            //     $userName = Client::find($idUser)->name;
            // }
            // $view->with('user', $userName);
            // $view->with('cart', Session::get('cart.total.quantity'));

            if (Session::get('id_seller')) {
                $idSeller = Session::get('id_seller');
                $sellerName = Seller::find($idSeller)->name;
                $view->with('seller', $sellerName);
            } elseif (Session::get('id_client')) {
                $idClient = Session::get('id_client');
                $clientName = Client::find($idClient)->name;
                $view->with('client', $clientName);
            }

            $view->with('cart_num', Session::get('cart.total.quantity'));
        });
    }
}

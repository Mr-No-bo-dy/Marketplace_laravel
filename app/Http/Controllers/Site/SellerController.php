<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Marketplace;
use App\Models\Site\Client;
use App\Models\Site\Order;
use App\Models\Site\Product;
use App\Models\Site\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    /**
     * Show one Seller's personal page.
     *
     * @param Request $request
     */
    public function show(Request $request)
    {
        $idSeller = $request->session()->get('id_seller');
        $sellerModel = new Seller();

        $seller = $sellerModel->readSellerWithCountry($idSeller);

        return view('profile.seller-show', compact('seller'));
    }

    /**
     * Display a listing of the Products from given Seller.
     *
     * @param Request $request
     */
    public function sellerProducts(Request $request)
    {
        $productModel = new Product();

        $idSeller = $request->session()->get('id_seller');
        $products = $productModel->readSellerProducts($idSeller);

        return view('site.seller.products', compact('products'));
    }

    /**
     * Display a listing of the Orders from given Seller.
     *
     * @param Request $request
     */
    public function sellerOrders(Request $request)
    {
        $idSeller = $request->session()->get('id_seller');
        $seller = Seller::find($idSeller);
        $orders = $seller->orders;

        foreach ($orders as $order) {
            if (isset($order->orderDetails)) {
                $order->id_product = $order->orderDetails->id_product;
                $order->count = $order->orderDetails->count;
                $order->total = $order->orderDetails->total;
            }

            $idClient = $order->id_client;
            $client = Client::find($idClient);
            if ($client) {
                $order->client_name = $client->name;
                $order->client_surname = $client->surname;
                $order->client_email = $client->email;
                $order->client_phone = $client->phone;
            }
        }

        return view('site.seller.orders', compact('seller', 'orders'));
    }

    /**
     * Update active Orders from given Seller.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function sellerOrdersUpdate(Request $request): RedirectResponse
    {
        $idOrder = $request->post('id_order');
        if ($request->has('order_accept')) {
            Order::where('id_order', $idOrder)
                    ->update(['status' => 'processed']);
        } elseif ($request->has('order_decline')) {
            Order::where('id_order', $idOrder)
                    ->update(['status' => 'declined']);
        }

        return redirect()->route('seller.my_orders');
    }

    /**
     * Show the form for editing the specified Seller.
     *
     * @param int $idSeller
     */
    public function edit($idSeller)
    {
        $sellerModel = new Seller();

        $seller = $sellerModel->readSeller($idSeller);
        $marketplaces = Marketplace::all(['id_marketplace', 'country']);

        return view('profile.seller-update', compact('marketplaces', 'seller'));
    }

    /**
     * Update the specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $sellerModel = new Seller();

        if ($request->has('updateSeller')) {
            $setSellerData = [
                'id_marketplace' => $request->post('id_marketplace'),
                'name' => $request->post('name'),
                'surname' => $request->post('surname'),
                'email' => $request->post('email'),
                'phone' => preg_replace("#[^0-9]#", "", $request->post('tel')),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $idSeller = $request->post('id_seller');
            $sellerModel->updateSeller($idSeller, $setSellerData);

            $setSellerPasswordData = [
                'password' => Hash::make($request->post('password')),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $sellerModel->updateSellerPassword($idSeller, $setSellerPasswordData);
        }

        return redirect()->route('seller.personal');
    }

    /**
     * Soft-Delete specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteSeller')) {
            $sellerModel = new Seller();
            $productModel = new Product();

            $idSeller = $request->post('id_seller');
            $productModel->deleteSellersProducts([$idSeller]);
            $sellerModel->deleteSeller($idSeller);
            $request->session()->forget('id_seller');
        }

        return redirect()->route('index');
    }
}

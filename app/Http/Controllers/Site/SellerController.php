<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Marketplace;
use App\Models\Site\Product;
use App\Models\Site\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    /**
     * Show one Seller's personal page.
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $sellerModel = new Seller();

        $idSeller = $request->session()->get('id_seller');
        $seller = $sellerModel->readSellerWithCountry($idSeller);

        return view('profile.seller-show', compact('seller'));
    }

    /**
     * Show the form for editing the specified Seller.
     *
     * @param int $idSeller
     * @return View
     */
    public function edit(int $idSeller): View
    {
        $sellerModel = new Seller();
        $marketplaceModel = new Marketplace();

        $seller = $sellerModel->readSeller($idSeller);
        $marketplaces = $marketplaceModel->readMarketplacesNames();

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

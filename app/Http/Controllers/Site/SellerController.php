<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\SellerRequest;
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
        $seller = Seller::findOrFail($idSeller);
        $marketplaces = Marketplace::all(['id_marketplace', 'country']);

        return view('profile.seller-update', compact('marketplaces', 'seller'));
    }

    /**
     * Update the specified Seller in storage.
     *
     * @param SellerRequest $request
     * @return RedirectResponse
     */
    public function update(SellerRequest $request): RedirectResponse
    {
        $sellerModel = new Seller();

        $status = false;
        $idSeller = $request->session()->get('id_seller');
        if ($sellerModel->passwordCheck($idSeller, $request->validated('password'))) {
            $seller = $sellerModel::findOrFail($idSeller);
            $seller->fill($request->safe()->except('password'));
            if ($seller->isDirty()) {
                $seller->save();
            }
            $status = true;
        }

        return $status ? back()->with('status', 'profileUpdated')
                        : back()->withErrors(['password' => trans('site_profile.wrongPassword')]);
    }

    /**
     * Update the specified Client's Password in storage.
     *
     * @param PasswordRequest $request
     * @return RedirectResponse
     */
    public function updatePass(PasswordRequest $request): RedirectResponse
    {
        $sellerModel = new Seller();

        $status = 'wrongPassword';
        $idSeller = $request->session()->get('id_seller');
        if ($sellerModel->passwordCheck($idSeller, $request->validated('old_password'))) {
            if ($request->validated('new_password') == $request->validated('new_password2')) {
                $setClientPasswordData = [
                    'password' => Hash::make($request->validated('new_password')),
                    'updated_at' => now(),
                ];
                $sellerModel->updateSellerPassword($idSeller, $setClientPasswordData);
                $status = 'success';
            } else {
                $status = 'differentPasswords';
            }
        }

        return $status == 'success' ? back()->with('status', 'passwordUpdated')
            : ($status == 'wrongPassword' ? back()->withErrors(['old_password' => trans('site_profile.wrongPassword')])
            : back()->withErrors(['new_password' => trans('site_profile.differentPasswords')]));
    }

    /**
     * Soft-Delete specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $status = false;
        if ($request->has('deleteSeller')) {
            $sellerModel = new Seller();
            $productModel = new Product();

            $password = $request->validate(['passwordForDelete' => ['bail', 'required', 'string', 'min:8', 'max:255']])['passwordForDelete'];
            $idSeller = $request->session()->get('id_seller');
            if ($sellerModel->passwordCheck($idSeller, $password)) {
                $productModel->deleteSellersProducts([$idSeller]);
                $sellerModel->deleteSeller($idSeller);
                $request->session()->forget('id_seller');
                $status = true;
            }
        }

        return $status ? redirect()->route('index')
                        : back()->withErrors(['passwordForDelete' => trans('site_profile.wrongPassword')]);
    }
}

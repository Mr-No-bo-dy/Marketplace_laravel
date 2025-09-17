<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\SellerRequest;
use App\Models\Marketplace;
use App\Models\Seller;
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
     * @return View|RedirectResponse
     */
    public function edit(int $idSeller): View|RedirectResponse
    {
        $seller = Seller::findOrFail($idSeller);
        $marketplaces = Marketplace::all(['id_marketplace', 'country']);

        return view('profile.seller-update', compact('marketplaces', 'seller'));
    }

    /**
     * Update the specified Seller in storage.
     *
     * @param SellerRequest $request
     * @param Seller $seller
     * @return RedirectResponse
     */
    public function update(SellerRequest $request, Seller $seller): RedirectResponse
    {
        $status = false;
        $idSeller = $request->session()->get('id_seller');
        if ($seller->passwordCheck($idSeller, $request->validated('password'))) {
            $seller->fill($request->safe()->except('password'));
            if ($seller->isDirty()) {
                $seller->save();
            }
            $status = true;
        }

        return $status ? back()->with('status', 'profileUpdated')
                        : back()->withErrors(['password' => trans('site_profile.wrongPassword')])->withInput();
    }

    /**
     * Update the specified Client's Password in storage.
     *
     * @param PasswordRequest $request
     * @param Seller $seller
     * @return RedirectResponse
     */
    public function updatePass(PasswordRequest $request, Seller $seller): RedirectResponse
    {
        $idSeller = $request->session()->get('id_seller');

        if (!$seller->passwordCheck($idSeller, $request->validated('old_password'))) {
            return back()->withErrors(['old_password' => trans('site_profile.wrongPassword')]);
        }

        if ($request->validated('new_password') !== $request->validated('new_password2')) {
            return back()->withErrors(['new_password' => trans('site_profile.differentPasswords')]);
        }

        $seller->updateSellerPassword($idSeller, [
            'password'   => Hash::make($request->validated('new_password')),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'passwordUpdated');
    }

    /**
     * Soft-Delete specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $sellerModel = new Seller();

        $status = false;
        $password = $request->validate(['passwordForDelete' => ['bail', 'required', 'string', 'min:8', 'max:255']])['passwordForDelete'];
        $idSeller = $request->session()->get('id_seller');
        if ($sellerModel->passwordCheck($idSeller, $password)) {
            $sellerModel->query()->findOrFail($idSeller)->delete();
            $request->session()->forget('id_seller');
            $status = true;
        }

        return $status ? redirect()->route('index')
                        : back()->withErrors(['passwordForDelete' => trans('site_profile.wrongPassword')]);
    }
}

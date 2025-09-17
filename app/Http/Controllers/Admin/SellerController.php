<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the Sellers.
     *
     * @return View
     */
    public function index(): View
    {
        $sellerModel = new Seller();

        $sellers = $sellerModel->readAllSellers();

        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Block the specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function block(Request $request): RedirectResponse
    {
        $idSeller = $request->validate(['id_seller' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_seller'];
        Seller::findOrFail($idSeller)->delete();
        $request->session()->forget('id_seller');

        return back();
    }

    /**
     * Restore the specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function unblock(Request $request): RedirectResponse
    {
        $idSeller = $request->validate(['id_seller' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_seller'];
        Seller::onlyTrashed()->findOrFail($idSeller)->restore();

        return back();
    }
}

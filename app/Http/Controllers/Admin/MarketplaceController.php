<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarketplaceRequest;
use App\Models\Admin\Marketplace;
use App\Models\Site\Product;
use App\Models\Site\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Display all Marketplaces
     *
     * @return View
     */
    public function index(): View
    {
        $marketplaces = Marketplace::withTrashed()->get();

        return view('admin.marketplaces.index', compact('marketplaces'));
    }

    /**
     * Display Marketplace creation form
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.marketplaces.create');
    }

    /**
     * Create Marketplace
     *
     * @param MarketplaceRequest $request
     * @return RedirectResponse
     */
    public function store(MarketplaceRequest $request): RedirectResponse
    {
        Marketplace::create($request->validated());

        return redirect()->route('admin.marketplace');
    }

    /**
     * Display Marketplace update form
     *
     * @param int $idMarketplace
     * @return View
     */
    public function edit(int $idMarketplace): View
    {
        $marketplace = Marketplace::findOrFail($idMarketplace);

        return view('admin.marketplaces.update', compact('marketplace'));
    }

    /**
     * Update Marketplace
     *
     * @param MarketplaceRequest $request
     * @return RedirectResponse
     */
    public function update(MarketplaceRequest $request): RedirectResponse
    {
        $idMarketplace = $request->validate(['id_marketplace' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_marketplace'];
        $marketplace = Marketplace::findOrFail($idMarketplace);
        $marketplace->fill($request->validated());
        if ($marketplace->isDirty()) {
            $marketplace->save();
        }

        return redirect()->route('admin.marketplace');
    }

    /**
     * Delete Marketplace, all its Sellers & Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteMarketplace')) {
            $sellerModel = new Seller();
            $productModel = new Product();

            $idMarketplace = $request->validate(['id_marketplace' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_marketplace'];
            $idsSeller = $sellerModel->deleteMarketplaceSellers($idMarketplace);
            $productModel->deleteSellersProducts($idsSeller);
            Marketplace::findOrFail($idMarketplace)->delete();
        }

        return back();
    }

    /**
     * Restore Marketplace, all its Sellers & Products
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        if ($request->has('restoreMarketplace')) {
            $sellerModel = new Seller();
            $productModel = new Product();

            $idMarketplace = $request->validate(['id_marketplace' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_marketplace'];
            Marketplace::onlyTrashed()->findOrFail($idMarketplace)->restore();
            $idsSeller = $sellerModel->restoreMarketplaceSellers($idMarketplace);
            $productModel->restoreSellerProducts($idsSeller);
        }

        return back();
    }
}

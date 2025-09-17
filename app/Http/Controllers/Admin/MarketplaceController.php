<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarketplaceRequest;
use App\Models\Marketplace;
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
     * @return View|RedirectResponse
     */
    public function edit(int $idMarketplace): View|RedirectResponse
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
        $idMarketplace = $request->validate(['id_marketplace' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_marketplace'];
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
        $idMarketplace = $request->validate(['id_marketplace' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_marketplace'];
        Marketplace::findOrFail($idMarketplace)->delete();

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
        $idMarketplace = $request->validate(['id_marketplace' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_marketplace'];
        Marketplace::onlyTrashed()->findOrFail($idMarketplace)->restore();

        return back();
    }
}

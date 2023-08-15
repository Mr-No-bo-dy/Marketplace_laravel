<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Marketplace;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Display all Marketplaces
     */
    public function index()
    {
        $marketplaces = Marketplace::all();

        return view('admin.marketplaces.index', compact('marketplaces'));
    }

    /**
     * Display Marketplace creation form
     */
    public function create()
    {
        return view('admin.marketplaces.create');
    }

    /**
     * Create Marketplace
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $marketplaceModel = new Marketplace();

        $postData = $request->post();
        $setMarketplaceData = [
            'country_code' => strtoupper($postData['country_code']),
            'country' => ucfirst($postData['country']),
            'currency' => strtoupper($postData['currency']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $marketplaceModel->storeMarketplace($setMarketplaceData);

        return redirect()->route('admin.marketplace');
    }

    /**
     * Display Marketplace update form
     */
    public function edit($idMarketplace)
    {
        $marketplace = Marketplace::find($idMarketplace);

        return view('admin.marketplaces.update', compact('marketplace'));
    }

    /**
     * Update Marketplace
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $marketplaceModel = new Marketplace();

        $postData = $request->post();
        $setMarketplaceData = [
            'country_code' => strtoupper($postData['country_code']),
            'country' => ucfirst($postData['country']),
            'currency' => strtoupper($postData['currency']),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $idMarketplace = $request->post('id_marketplace');
        $marketplaceModel->updateMarketplace($idMarketplace, $setMarketplaceData);

        return redirect()->route('admin.marketplace');
    }

    /**
     * Delete Marketplace
     */
    public function destroy(Request $request): RedirectResponse
    {
        $marketplaceModel = new Marketplace();

        $idMarketplace = $request->post('id_marketplace');
        $marketplaceModel->deleteMarketplace($idMarketplace);

        return redirect()->route('admin.marketplace');
    }
}

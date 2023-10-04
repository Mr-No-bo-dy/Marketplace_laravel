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
        $marketplaceModel = new Marketplace();

        $marketplaces = $marketplaceModel->readAllMarketplaces();

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
        if ($request->has('createMarketplace')) {
            $marketplaceModel = new Marketplace();

            $setMarketplaceData = [
                'country_code' => strtoupper($request->post('country_code')),
                'country' => ucfirst($request->post('country')),
                'currency' => strtoupper($request->post('currency')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $marketplaceModel->storeMarketplace($setMarketplaceData);
        }

        return redirect()->route('admin.marketplace');
    }

    /**
     * Display Marketplace update form
     *
     * @param int $idMarketplace
     */
    public function edit(int $idMarketplace)
    {
        $marketplaceModel = new Marketplace();

        $marketplace = $marketplaceModel->reaMarketplace($idMarketplace);

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
        if ($request->has('updateMarketplace')) {
            $marketplaceModel = new Marketplace();

            $setMarketplaceData = [
                'country_code' => strtoupper($request->post('country_code')),
                'country' => ucfirst($request->post('country')),
                'currency' => strtoupper($request->post('currency')),
            ];
            $idMarketplace = $request->post('id_marketplace');
            $marketplaceModel->updateMarketplace($idMarketplace, $setMarketplaceData);
        }

        return redirect()->route('admin.marketplace');
    }

    /**
     * Delete Marketplace
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteMarketplace')) {
            $marketplaceModel = new Marketplace();

            $idMarketplace = $request->post('id_marketplace');
            $marketplaceModel->deleteMarketplace($idMarketplace);
        }

        return back();
    }
}

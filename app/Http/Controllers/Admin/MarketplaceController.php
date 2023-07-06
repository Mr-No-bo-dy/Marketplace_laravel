<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Marketplace;
use App\Http\Controllers\Controller;
// use App\Http\Requests\MarketplaceRequest;

class MarketplaceController extends Controller
{
    /**
     * Display all Marketplaces
     */
    public function show()
    {
        $marketplaceModel = new Marketplace();

        $marketplaces = $marketplaceModel->all();

        return view('admin.marketplaces.index', ['marketplaces' => $marketplaces]);
    }
    
    /**
     * Display Marketplace creation form
     */
    public function create()
    {
        return view('admin.marketplaces.create');
    }

    /**
     * Display Marketplace update form
     */
    public function edit($idMarketplace)
    {
        $marketplaceModel = new Marketplace();

        $marketplace = $marketplaceModel->find($idMarketplace);

        return view('admin.marketplaces.update', ['marketplace' => $marketplace]);
    }

    /**
     * Store (Create or Update) Marketplace
     * 
     * @param object \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $marketplaceModel = new Marketplace();

        $postData = $request->post();
        $setMarketplaceData = [
            'country_code' => strtoupper($postData['country_code']),
            'country' => ucfirst($postData['country']),
            'currency' => strtoupper($postData['currency']),
        ];
        
        $idMarketplace = $request->post('id_marketplace');
        if (!empty($idMarketplace)) {
            $marketplaceModel->updateMarketplace($idMarketplace, $setMarketplaceData);
        } else {
            $marketplaceModel->storeMarketplace($setMarketplaceData);
        }

        return redirect()->route('admin.marketplace');
    }

    /**
     * Delete Marketplace
     */
    public function delete(Request $request)
    {
        $marketplaceModel = new Marketplace();

        $idMarketplace = $request->post('id_marketplace');
        $marketplaceModel->deleteMarketplace($idMarketplace);

        return redirect()->route('admin.marketplace');
    }
}

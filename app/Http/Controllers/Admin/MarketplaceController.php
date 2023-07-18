<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Marketplace;
use App\Http\Controllers\Controller;
// use App\Http\Requests\MarketplaceRequest;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
   /**
    * Display all Marketplaces
    */
   public function index()
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
    * Create Marketplace
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
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $marketplaceModel->storeMarketplace($setMarketplaceData);

      return redirect()->route('admin.marketplace');
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
    * Update Marketplace
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function update(Request $request)
   {
      $marketplaceModel = new Marketplace();

      $postData = $request->post();
      $setMarketplaceData = [
         'country_code' => strtoupper($postData['country_code']),
         'country' => ucfirst($postData['country']),
         'currency' => strtoupper($postData['currency']),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $idMarketplace = $request->post('id_marketplace');
      $marketplaceModel->updateMarketplace($idMarketplace, $setMarketplaceData);

      return redirect()->route('admin.marketplace');
   }

   /**
    * Delete Marketplace
    */
   public function destroy(Request $request)
   {
      $marketplaceModel = new Marketplace();

      $idMarketplace = $request->post('id_marketplace');
      $marketplaceModel->deleteMarketplace($idMarketplace);

      return redirect()->route('admin.marketplace');
   }
}

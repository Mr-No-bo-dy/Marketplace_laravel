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
         'created_at' => date('Y-m-d H:i:s', time()),
         'updated_at' => date('Y-m-d H:i:s', time()),
      ];
      $marketplaceModel->storeMarketplace($setMarketplaceData);
      /**
      * Next create method will automaticaly fill 'created_at' & 'updated_at' fields
      * without need to set them & without model's similar method:
      */
      // $marketplaceModel::create($setMarketplaceData);

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
         'updated_at' => date('Y-m-d H:i:s', time()),
      ];
      $idMarketplace = $request->post('id_marketplace');
      $marketplaceModel->updateMarketplace($idMarketplace, $setMarketplaceData);
      /**
      * Next update methods will automaticaly fill 'updated_at' field
      * without need to set them & without model's similar method:
      */
      // $marketplaceModel::where('id_marketplace', $idMarketplace)
      //                     ->update($setMarketplaceData);

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

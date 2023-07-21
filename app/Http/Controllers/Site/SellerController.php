<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Marketplace;
use App\Models\Site\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
   /**
    * Display a listing of the Sellers.
    */
   public function index()
   {
      $sellerModel = new Seller();

      $sellers = $sellerModel->getAllSellers();

      return view('site.seller.index', ['sellers' => $sellers]);
   }

   /**
    * Show the form for creating a new Seller.
    */
   public function create()
   {
      $marketplaceModel = new Marketplace();

      $marketplaces = $marketplaceModel->all(['id_marketplace', 'country']);

      return view('site.seller.create', ['marketplaces' => $marketplaces]);
   }

   /**
    * Store a newly created Seller in storage.
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function store(Request $request)
   {
      $sellerModel = new Seller();

      $postData = $request->post();
      $setSellerData = [
         'id_marketplace' => $postData['id_marketplace'],
         'name' => $postData['name'],
         'surname' => $postData['surname'],
         'email' => $postData['email'],
         'phone' => $postData['tel'],
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      
      $idNewSeller = $sellerModel->storeSeller($setSellerData);
      $setSellerPasswordData = [
         'id_seller' => $idNewSeller,
         'password' => Hash::make($postData['password']),
         'created_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $sellerModel->storeSellerPassword($setSellerPasswordData);
      

      return redirect()->route('seller');
   }

   /**
    * Show the form for editing the specified Seller.
    * 
    * @param int $idSeller
    */
   public function edit($idSeller)
   {
      $marketplaceModel = new Marketplace();
      $sellerModel = new Seller();
      
      $marketplaces = $marketplaceModel->all(['id_marketplace', 'country']);
      $oneSeller = $sellerModel->getOneSeller($idSeller);
      $seller = [];
      foreach ($oneSeller as $row) {
         $seller = $row;
      }
      $setSellerData = [
         'seller' => $seller,
         'marketplaces' => $marketplaces,
      ];
      
      return view('site.seller.update', $setSellerData);
   }

   /**
    * Update the specified Seller in storage.
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function update(Request $request)
   {        
      $sellerModel = new Seller();

      $postData = $request->post();
      $setSellerData = [
         'id_marketplace' => $postData['id_marketplace'],
         'name' => $postData['name'],
         'surname' => $postData['surname'],
         'email' => $postData['email'],
         'phone' => $postData['tel'],
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      
      $idSeller = $request->post('id_seller');
      $sellerModel->updateSeller($idSeller, $setSellerData);
      $setSellerPasswordData = [
         'password' => Hash::make($postData['password']),
         'updated_at' => date('y.m.d H:i:s', strtotime('+3 hour')),
      ];
      $sellerModel->updateSellerPassword($idSeller, $setSellerPasswordData);
         
      return redirect()->route('seller');
   }

   /**
    * Remove the specified resource from storage.
    * 
    * @param object \Illuminate\Http\Request $request
    */
   public function destroy(Request $request)
   {
      $sellerModel = new Seller();

      $idSeller = $request->post('id_seller');
      $sellerModel->deleteSeller($idSeller);

      return redirect()->route('seller');
   }
   
   /**
    * Show one Seller's personal page.
   */
   public function show(Request $request)
   {
      $sellerModel = new Seller();
      
      $idSeller = $request->session()->get('id_seller');
      $oneSeller = $sellerModel->getOneSeller($idSeller);
      $seller = [];
      foreach ($oneSeller as $row) {
         $seller = $row;
      }
      
      return view('site.seller.show', ['seller' => $seller]);
   }
}

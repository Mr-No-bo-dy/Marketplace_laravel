<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Admin\Marketplace;
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

      return view('seller.index', ['sellers' => $sellers]);
   }

   /**
    * Display the specified Seller.
    */
   public function show(Seller $seller)
   {
      //
   }

   /**
    * Show the form for creating a new Seller.
    */
   public function create()
   {
      $marketplaceModel = new Marketplace();

      $marketplaces = $marketplaceModel->all();

      return view('seller.create', ['marketplaces' => $marketplaces]);
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
      ];
      
      $idNewSeller = $sellerModel->storeSeller($setSellerData);
      $setSellerPasswordData = [
         'id_seller' => $idNewSeller,
         'password' => Hash::make($postData['password']),
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
      
      $marketplaces = $marketplaceModel->all();
      $oneSeller = $sellerModel->getOneSeller($idSeller);
      $seller = [];
      foreach ($oneSeller as $row) {
         $seller = $row;
      }
      $setSellerData = [
         'seller' => $seller,
         'marketplaces' => $marketplaces,
      ];
      
      return view('seller.update', $setSellerData);
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
      ];
      
      $idSeller = $request->post('id_seller');
      $sellerModel->updateSeller($idSeller, $setSellerData);
      $setSellerPasswordData = [
         'password' => Hash::make($postData['password']),
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
}

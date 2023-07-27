<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Marketplace;
use App\Models\Site\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
   // /**
   //  * Display a listing of the Sellers.
   //  */
   // public function index()
   // {
   //    $sellerModel = new Seller();

   //    $sellers = $sellerModel->getAllSellers();

   //    return view('site.seller.index', compact('sellers'));
   // }

   /**
    * Show one Seller's personal page.
   */
   public function show(Request $request)
   {
      $sellerModel = new Seller();
      
      $idSeller = $request->session()->get('id_seller');
      $seller = $sellerModel->getOneSeller($idSeller);
      
      return view('profile-seller.show', compact('seller'));
   }

   /**
    * Show the form for editing the specified Seller.
    * 
    * @param int $idSeller
    */
   public function edit($idSeller)
   {
      $sellerModel = new Seller();
      
      $marketplaces = Marketplace::all(['id_marketplace', 'country']);
      $seller = $sellerModel->getOneSeller($idSeller);
      
      return view('profile-seller.update', compact('marketplaces', 'seller'));
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
         
      return redirect()->route('personal');
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

      return redirect()->route('index');
   }
}

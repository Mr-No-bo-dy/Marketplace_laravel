<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Admin\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GeneralController extends Controller
{
   /**
    * Display site's Home page.
    */
   public function index()
   {
      return view('index');
   }

   /**
    * Show the form for Registrating a new Seller.
    */
   public function register()
   {
      $marketplaceModel = new Marketplace();

      $marketplaces = $marketplaceModel->all();

      $content = [
         'marketplaces' => $marketplaces,
      ];
      
      return view('authentificate.register', $content);
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
      ];
      $sellerModel->storeSellerPassword($setSellerPasswordData);

      $seller = $sellerModel->get($idNewSeller);
      $request->session()->put('seller', $seller);

      return redirect()->route('auth');
   }

   /**
   * Login Seller into Personal Page.
   * 
   * @param object \Illuminate\Http\Request $request
   */
   public function auth(Request $request)
   {
      $sellerModel = new Seller();

      $postData = $request->post();

      if (!empty($postData)) {
         $data = [
            'login' => $postData['login'],
            'password' => $postData['password'],
         ];

         $oneSeller = $sellerModel->authSeller($data);

         if (!empty($oneSeller)) {
            $request->session()->put('seller', $oneSeller);
            
            return redirect()->route('personal');
         }
      }
      
      return view('authentificate.login');
   }


}

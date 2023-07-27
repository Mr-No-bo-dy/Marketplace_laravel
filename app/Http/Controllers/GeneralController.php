<?php

namespace App\Http\Controllers;

use App\Models\Admin\Marketplace;
use App\Models\Site\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GeneralController extends Controller
{
   /**
    * Display site's Home page.
    */
   public function index(Request $request)
   {
      $sellerModel = new Seller();

      $seller = "";
      if ($idSeller = $request->session()->get('id_seller')) {
         $seller = $sellerModel->getOneSeller($idSeller);
      }
      
      return view('index', compact('seller'));
   }

   /**
    * Show the form for Registrating a new Seller.
    */
   public function register()
   {
      $marketplaces = Marketplace::all(['id_marketplace', 'country']);
      
      return view('authentificate.register', compact('marketplaces'));
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

      $request->session()->put('id_seller', $idNewSeller);

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

      if ($request->session()->has('id_seller')) {
         return redirect()->route('personal');
      }

      $postData = $request->post();
      if (!empty($postData)) {
         $data = [
            'login' => $postData['login'],
            'password' => $postData['password'],
         ];
         $idAuthSeller = $sellerModel->authSeller($data);

         if (!empty($idAuthSeller)) {
            $request->session()->put('id_seller', $idAuthSeller);
            
            return redirect()->route('personal');
         }
      }
      
      return view('authentificate.login');
   }

   /**
   * Logout Seller from Site.
   * 
   * @param object \Illuminate\Http\Request $request
   */
   public function logout(Request $request)
   {
      $request->session()->forget('id_seller');

      return redirect()->route('index');
   }

}

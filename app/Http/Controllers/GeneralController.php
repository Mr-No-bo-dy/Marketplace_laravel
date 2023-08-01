<?php

namespace App\Http\Controllers;

use App\Models\Site\Client;
use App\Models\Site\Seller;
use Illuminate\Http\Request;
use App\Models\Admin\Marketplace;
use Illuminate\Support\Facades\Hash;

class GeneralController extends Controller
{
   /**
   * Display site's Home page.
   */
   public function index(Request $request)
   {
      $seller = $client = null;
      if ($request->session()->has('id_seller')) {
         $idSeller = $request->session()->get('id_seller');
         $seller = Seller::find($idSeller);
         
      } elseif ($request->session()->has('id_client')) {
         $idClient = $request->session()->get('id_client');
         $client = Client::find($idClient);
      }
      
      return view('index', compact('seller', 'client'));
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
   * Show the form for Registrating a new Client.
   */
   public function registerClient()
   {
      return view('authentificate.registerClient');
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
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $idNewSeller = $sellerModel->storeSeller($setSellerData);
      
      $setSellerPasswordData = [
         'id_seller' => $idNewSeller,
         'password' => Hash::make($postData['password']),
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $sellerModel->storeSellerPassword($setSellerPasswordData);

      $request->session()->put('id_seller', $idNewSeller);

      return redirect()->route('auth');
   }

   /**
   * Store a newly created Client in storage.
   * 
   * @param object \Illuminate\Http\Request $request
   */
   public function storeClient(Request $request)
   {
      $clientModel = new Client();

      $postData = $request->post();
      $setClientData = [
         'name' => $postData['name'],
         'surname' => $postData['surname'],
         'email' => $postData['email'],
         'phone' => $postData['tel'],
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $idNewClient = $clientModel->storeClient($setClientData);
      
      $setClientPasswordData = [
         'id_client' => $idNewClient,
         'password' => Hash::make($postData['password']),
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $clientModel->storeClientPassword($setClientPasswordData);

      $request->session()->put('id_client', $idNewClient);

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
      $clientModel = new Client();

      if ($request->session()->has('id_seller')) {
         return redirect()->route('seller.personal');
      } elseif ($request->session()->has('id_client')) {
         return redirect()->route('client.personal');
      }

      $route = view('authentificate.login');

      $postData = $request->post();
      if (!empty($postData)) {
         $data = [
            'login' => $postData['login'],
            'password' => $postData['password'],
         ];
         
         $idAuthUser = $sellerModel->authSeller($data);
         if (!empty($idAuthUser)) {
            $request->session()->put('id_seller', $idAuthUser);
            $route = redirect()->route('seller.personal');

         } else {
            $idAuthUser = $clientModel->authClient($data);
            $request->session()->put('id_client', $idAuthUser);
            $route = redirect()->route('client.personal');
         }
      }
      
      return $route;
   }

   /**
   * Login Client into Personal Page.
   * 
   * @param object \Illuminate\Http\Request $request
   */
   public function authClient(Request $request)
   {
      $clientModel = new Client();

      if ($request->session()->has('id_client')) {
         return redirect()->route('client.personal');
      }

      $postData = $request->post();
      if (!empty($postData)) {
         $data = [
            'login' => $postData['login'],
            'password' => $postData['password'],
         ];
         $idAuthClient = $clientModel->authClient($data);

         if (!empty($idAuthClient)) {
            $request->session()->put('id_client', $idAuthClient);
            
            return redirect()->route('client.personal');
         }
      }
      
      return view('authentificate.login');
   }

   /**
   * Logout Seller and Client from Site.
   * 
   * @param object \Illuminate\Http\Request $request
   */
   public function logout(Request $request)
   {
      $request->session()->forget(['id_seller', 'id_client']);

      return redirect()->route('index');
   }

   /**
   * Adding to cart Products.
   * 
   * @param object \Illuminate\Http\Request $request
   */
   public function addToCart(Request $request)
   {
      $idProduct = $request->post('id_product');
      // $cart = $request->session()->forget('cart');

      // $cart = $request->session()->get('cart', []);
      // $cart[$idProduct] = $idProduct;
      // $request->session()->put('cart', $idProduct);

      // $key = 'cart.product.'.$idProduct;
      // $request->session()->put($key, $idProduct);
      
      $request->session()->put('cart.product.'.$idProduct, $idProduct);

      $cart = $request->session()->all();
      dd($cart);

   }
}

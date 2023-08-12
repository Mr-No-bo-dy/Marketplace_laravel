<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Marketplace;
use App\Models\Site\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
   /**
   * Display a listing of the Sellers.b
   */
   public function index()
   {
      $sellers = Seller::withTrashed()->get();

      return view('admin.sellers.index', compact('sellers'));
   }

   /**
   * Show one Seller's personal page.
   */
   public function show(Request $request)
   {
      $idSeller = $request->session()->get('id_seller');

      $seller = Seller::find($idSeller);

      return view('profile.seller-show', compact('seller'));
   }

   /**
   * Display a listing of the Products from given Seller.
   */
   public function sellerProducts(Request $request)
   {
      $idSeller = $request->session()->get('id_seller');

      $seller = Seller::find($idSeller);
      $products = $seller->products;

      return view('site.seller.products', compact('seller', 'products'));
   }

   /**
   * Show the form for editing the specified Seller.
   *
   * @param int $idSeller
   */
   public function edit($idSeller)
   {
      $marketplaces = Marketplace::all(['id_marketplace', 'country']);
      $seller = Seller::find($idSeller);

      return view('profile.seller-update', compact('marketplaces', 'seller'));
   }

    /**
     * Update the specified Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
   public function update(Request $request): RedirectResponse
   {
      $sellerModel = new Seller();

      $postData = $request->post();
      $setSellerData = [
         'id_marketplace' => $postData['id_marketplace'],
         'name' => $postData['name'],
         'surname' => $postData['surname'],
         'email' => $postData['email'],
         'phone' => $postData['tel'],
         'updated_at' => date('Y-m-d H:i:s'),
      ];

      $idSeller = $request->post('id_seller');
      $sellerModel->updateSeller($idSeller, $setSellerData);
      $setSellerPasswordData = [
         'password' => Hash::make($postData['password']),
         'updated_at' => date('Y-m-d H:i:s'),
      ];
      $sellerModel->updateSellerPassword($idSeller, $setSellerPasswordData);

      return redirect()->route('seller.personal');
   }

    /**
     * Block the specified Seller (soft delete).
     *
     * @param Request $request
     * @return RedirectResponse
     */
   public function block(Request $request): RedirectResponse
   {
      $idSeller = $request->post('id_seller');
      $seller = Seller::find($idSeller);
      $seller->delete();

      return redirect()->route('admin.seller');
   }

    /**
     * UnBlock the specified Seller (soft delete).
     *
     * @param Request $request
     * @return RedirectResponse
     */
   public function unblock(Request $request): RedirectResponse
   {
      $idSeller = $request->post('id_seller');
      $seller = Seller::onlyTrashed()->find($idSeller);
      $seller->restore();

      return redirect()->route('admin.seller');
   }

    /**
     * Remove the specified Seller from storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
   public function destroy(Request $request): RedirectResponse
   {
      $sellerModel = new Seller();

      $idSeller = $request->post('id_seller');
      $sellerModel->deleteSeller($idSeller);

      $request->session()->forget('id_seller');

      return redirect()->route('index');
   }
}

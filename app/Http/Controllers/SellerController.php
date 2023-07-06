<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Admin\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marketplaceModel = new Marketplace();

        $marketplaces = $marketplaceModel->all();

        return view('seller.create', ['marketplaces' => $marketplaces]);
    }

    /**
     * Store a newly created resource in storage.
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
        
        // $idSeller = $request->post('id_seller');
        // if (!empty($idSeller)) {
        //     $sellerModel->updateSellerPassword($idSeller, $setSellerData);
        //     $sellerModel->updateSellerPassword($setSellerPasswordData);
        // } else {
            $idSeller = $sellerModel->storeSeller($setSellerData);
            $setSellerPasswordData = [
                'id_seller' => $idSeller,
                'password' => Hash::make($postData['password']),
            ];
            $sellerModel->storeSellerPassword($setSellerPasswordData);
        // }

        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        //
    }
}

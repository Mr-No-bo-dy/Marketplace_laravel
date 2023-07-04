<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class GeneralController extends Controller
{
    public function index()
    {
        // $allSellers = Seller::all();
        $sellerModel = new Seller();
        $allSellers = $sellerModel->getSellers();

        return view('sellers.sellers', ['sellers' => $allSellers]);
    }

    public function create()
    {
        
    }

}

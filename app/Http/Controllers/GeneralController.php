<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class GeneralController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create()
    {
        
    }

}

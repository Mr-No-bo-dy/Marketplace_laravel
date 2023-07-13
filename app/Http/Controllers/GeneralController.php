<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
   public function index()
   {
      return view('index');
   }

   public function auth(Request $request)
   {
      // session('key');
      return $request->session();
   }

   public function login(Request $request)
   {
      dd($this->auth($request));
      return view('seller.login');
   }
}

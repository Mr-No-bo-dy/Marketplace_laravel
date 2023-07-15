<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;

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

   public function login(Request $request, Session $session)
   {
      $seller = new Seller();

      $postData = $request->post();
      if (!empty($postData)) {
         $data = [
            'login' => $postData['login'],
            'password' => $postData['password'],
         ];
         $oneSeller = $seller->authSeller($data);
   
         $is = $request->session()->isStarted();
         
         if (!empty($oneSeller)) {
            $request->session()->put('name', $oneSeller->name);
            // $session->put('name', $oneSeller->name);
         }
         // $name = $request->session()->forget('name');
         $all = $request->session()->all();
         dd($all);
         
         // qfaM4J8zKlvmyQYa8ktIUDNTHoIpgMvlVRJJT3qW

      }
      
      // $seller = Auth::user();

      return view('authentificate.login');
   }
}

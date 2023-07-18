<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerAuth
{
   /**
    * Get the path the user should be redirected to when they are not authenticated.
    */
   public function handle(Request $request, Closure $next)
   {
      return $request->session()->has('seller') ? $next($request) : redirect()->route('auth');
   }
}

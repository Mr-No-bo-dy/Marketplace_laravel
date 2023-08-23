<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWithLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $preferredLocale = $request->getPreferredLanguage(config('app.locales'));
//        dd(app());

        if (in_array($request->segment(1), config('app.locales'))) {
//            dd($request->getRequestUri());

//            $routeName = $next($request)->route()->getName();
//            dd($routeName);
//            return redirect()->route($routeName);
            return $next($request);
        } else {

//            dd(config('app.url'), $preferredLocale, $request->getPathInfo());
            return redirect(config('app.url') . '/' . $preferredLocale . $request->getRequestUri());
        }

//        $route = config('app.url') . '/' . $preferredLocale . $request->getRequestUri();
//        return redirect($route);

    }
}

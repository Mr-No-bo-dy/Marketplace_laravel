<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set app's locale from value in Cookie
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('app_locale', config('app.locale'));
        if (!in_array($locale, config('app.available_locales'))) {
            $locale = config('app.locale');
        }
        App::setLocale($locale);

        return $next($request);
    }
}

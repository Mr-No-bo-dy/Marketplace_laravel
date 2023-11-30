<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultLocaleForUrls
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->validateRequest($request);

        $lang = $request->post('lang');
        if (in_array($lang, ['en', 'uk'])) {
            $request->session()->put('locale', $lang);
        }

        $request->session()->has('locale') ? app()->setLocale($request->session()->get('locale')) : app()->setLocale(config('app.fallback_locale'));

        // Adding locale prefix itself
        URL::defaults(['locale' => app()->getLocale()]);

        return $next($request);
    }

    /**
     * Validate the incoming request data.
     *
     * @param Request $request
     */
    protected function validateRequest(Request $request): void
    {
        try {
            $validator = validator($request->all(), [
                'lang' => ['sometimes', 'in:en,uk'],
            ]);
            $validator->validate();

        } catch (ValidationException $e) {
            // Log the error or something like that...
        }
    }
}

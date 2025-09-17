<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GeneralController extends Controller
{
    /**
     * Switch app's locale.
     *
     * @param string $locale
     * @return RedirectResponse
     */
    public function switch(string $locale): RedirectResponse
    {
        if (in_array($locale, config('app.available_locales'))) {
            return back()->withCookie(cookie('app_locale', $locale, 60*24*30));
        }
        return back();
    }

    /**
     * Display site's Home page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('index');
    }
}

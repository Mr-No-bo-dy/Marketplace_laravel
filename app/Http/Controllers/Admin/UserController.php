<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a Dashboard page.
     *
     * @return View
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Read & Display a listing of the Admins.
     *
     * @return View
     */
    public function admins(): View
    {
        $admins = User::withTrashed()->get();

        return view('admin.admins.admins', compact('admins'));
    }

    /**
     * Delete the specified Admin in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::user()->id !== 1) {
            abort(403);
        }

        $id = $request->validate(['id' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id'];
        User::findOrFail($id)->delete();

        return back();
    }

    /**
     * Restore the specified Admin in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        if (Auth::user()->id !== 1) {
            abort(403);
        }

        $id = $request->validate(['id' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id'];
        User::onlyTrashed()->findOrFail($id)->restore();

        return back();
    }
}

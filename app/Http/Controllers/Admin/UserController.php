<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('admin.admins.index', compact('admins'));
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

        $id = $request->validate(['id' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id'];
        if ($id != 1) User::findOrFail($id)->delete();

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

        $id = $request->validate(['id' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id'];
        User::onlyTrashed()->findOrFail($id)->restore();

        return back();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;

class AdminController extends Controller
{
    /**
     * Display a Dashboard page.
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Display a listing of the Admins.
     */
    public function admins()
    {
        $userModel = new User();

        $admins = $userModel->getAdmins();

        return view('admin.admins.admins', compact('admins'));
    }
}

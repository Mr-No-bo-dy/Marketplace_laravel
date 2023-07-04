<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User;

class UserController extends Controller
{
    public function users()
    {
        $userModel = new User();
        $users = $userModel->getUsers();

        return view('admin.users.users', ['users' => $users]);
    }
}

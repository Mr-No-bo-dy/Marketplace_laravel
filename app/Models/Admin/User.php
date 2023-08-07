<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
   use HasFactory;

   public function getUsers()
   {
      return $users = DB::table('users')
                        ->select()
                        ->where('email', '!=', null)
                        ->get();
   }
}

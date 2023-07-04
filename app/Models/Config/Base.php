<?php

namespace App\Models\Config;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public function __construct()
    {
        return new DB();
    }
}

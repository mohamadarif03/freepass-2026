<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{

    public static function getUserRole(): string
    {
        return Auth::user()->role;
    }


    public static function getUserName(): string
    {
        return Auth::user()->name;
    }


    public static function getUserEmail(): string
    {
        return Auth::user()->email;
    }

}

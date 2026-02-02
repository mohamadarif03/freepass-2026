<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{

    public static function getUserRole(): string
    {
        return Auth::user()->roles->pluck('name')[0];
    }


    public static function getUserId(): string
    {
        return Auth::user()->id;
    }


    public static function getUserName(): string
    {
        return Auth::user()->name;
    }


    public static function getUserEmail(): string
    {
        return Auth::user()->email;
    }


    public static function getUserPhoto(): string|null
    {
        return Auth::user()->photo;
    }
}

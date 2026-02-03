<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canteen extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'location',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}

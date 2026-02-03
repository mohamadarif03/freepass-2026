<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'menu_id',
        'quantity'
    ];
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}

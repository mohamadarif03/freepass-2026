<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'payment_method',
        'status_payment',
        'status',
        'total_amount'
    ];

    public function menus()
    {
        return $this->hasMany(MenuTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

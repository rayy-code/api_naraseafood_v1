<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    //
    protected $table = "payments";
    protected $fillable = [
        'idPayment',
        'idOrder',
        'total_price',
        'pay',
        'excessMoney'
    ];

    public function order():HasOne
    {
        return $this->hasOne(Order::class,'idOrder','idOrder');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DrinkCart extends Model
{
    //
    protected $fillable = [
        'idDrinkCart',
        'idDrink',
        'qty',
        'totalPrice'
    ];

    public function drink(): HasOne
    {
        return $this->hasOne(Drink::class,'idDrink','idDrink');
    }
}

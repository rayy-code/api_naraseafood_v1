<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    //
    protected $table= "orders";
    protected $fillable = [
        'idOrder',
        'idMealCart',
        'idDrinkCart',
        'total_price'
    ];

    public function meal_carts():HasMany
    {
        return $this->hasMany(MealCart::class,'idMealCart',"idMealCart");
    }
    public function drink_carts():hasMany
    {
        return $this->hasMany(DrinkCart::class,'idDrinkCart',"idDrinkCart");
    }
}

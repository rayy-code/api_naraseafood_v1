<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MealCart extends Model
{
    //
    protected $fillable = [
        'idMealCart',
        'idMeal',
        'qty',
        'totalPrice'
    ];

    public function meals():HasMany
    {
        return $this->hasMany(Meal::class);
    }

    public function meal():HasOne
    {
        return $this->hasOne(Meal::class,'idMeal','idMeal');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    //
    protected $table = "meals";
    protected $primaryKey = "idMeal";
    protected $keyType = "string";
    public $incrementing = "false";
    protected $fillable =[
        "idMeal",
        "strMeal",
        "strMealThumb",
        "price"
    ];
    
/**
     * strMealThumb
     *
     * @return Attribute
     */
    protected function strMealThumb(): Attribute
    {
        return Attribute::make(
            get: fn ($strMealThumb) => url('/storage/meals/' . $strMealThumb),
        );
    }

    public function mealCarts():BelongsTo
    {
        return $this->belongsTo('meal_carts','idMeal','idMeal');
    }
}

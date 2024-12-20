<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Drink extends Model
{
    //
    protected $table = "drinks";
    protected $primaryKey = "idDrink";
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        'idDrink',
        'strDrink',
        'strDrinkThumb',
        'price'
    ];
    /**
     * strDrinkThumb
     *
     * @return Attribute
     */
    protected function strDrinkThumb(): Attribute
    {
        return Attribute::make(
            get: fn ($strDrinkThumb) => url('/storage/drinks/' . $strDrinkThumb),
        );
    }
}

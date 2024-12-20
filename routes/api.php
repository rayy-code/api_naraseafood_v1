<?php

use App\Http\Controllers\Api\DrinkCartController;
use App\Http\Controllers\Api\DrinkController;
use App\Http\Controllers\Api\MealCartController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/meals',MealController::class);

Route::apiResource('/drinks',DrinkController::class);

Route::apiResource('/meal_carts',MealCartController::class);

Route::apiResource('/drink_carts',DrinkCartController::class);

Route::apiResource('/orders',OrderController::class);

Route::apiResource('/payments',PaymentController::class);

Route::get('/sales/{date}', [PaymentController::class,'sales']);

Route::get('/meal-cart/{idMealCart}/{idMeal}',[MealCartController::class,'single']);

Route::delete('/meal-cart/d/{idMealCart}/{idMeal}',[MealCartController::class,'delete']);

//Route::get('/order/{idMealCart}/{idDrinkCart}', [OrderController::class,'getTotalOrder']);

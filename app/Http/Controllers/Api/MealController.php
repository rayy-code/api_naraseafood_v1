<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    //index
    public function index()
    {
        $meals = Meal::all();

        return new MealResource(true, "Data Meals",["meals"=>$meals]);
    }

    //show
    public function show($idMeal)
    {
        $meal = Meal::where('idMeal','=',$idMeal)->first();
        if (!$meal) {
            return new MealResource(false, "Meal Not Found",null);
        }else{
            return new MealResource(true, "Meal Found",["meal"=>$meal]);
        }
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'strMeal' => "required|string",
            "strMealThumb" => "required|image|mimes:png,jpg,jpeg|max:2048",
            "price" => "required|integer"
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $strMealThumb = $request->file('strMealThumb');
        $strMealThumb->storeAs('images/meals', $strMealThumb->hashName());

        //create post
        $post = Meal::create([
            'idMeal' => Str::uuid(),
            'strMeal' => $request->strMeal,
            'strMealThumb' => $strMealThumb->hashName(),
            "price" => $request->price
        ]);

        return new MealResource(true, "Data stored",[]);
    }

    //delete
    public function destroy($idMeal){
        $meal = Meal::where("idMeal",'=',$idMeal)->first();
        if (!$meal) {
            return response()->json(["message" => "Meal not found"], 404);
        }else{
            Storage::delete('public/images/meals/'.basename($meal->strMealThumb));
            $meal->delete();
            return new MealResource(true, "Meal deleted",[]);
        }
    }
}

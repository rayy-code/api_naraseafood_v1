<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DrinkResource;
use App\Models\Drink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DrinkController extends Controller
{
    //index
    public function index()
    {
        $drinks = Drink::all();

        return new DrinkResource(true, "Data Drinks",["drinks"=>$drinks]);
    }

    //store
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'strDrink' => 'required|string',
            'strDrinkThumb' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            "price" => "required|integer"
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $strDrinkThumb = $request->file(key: 'strDrinkThumb');
        $strDrinkThumb->storeAs('images/drinks', $strDrinkThumb->hashName());

        //create post
        $post = Drink::create([
            'idDrink' => Str::uuid(),
            'strDrink' => $request->strDrink,
            'strDrinkThumb' => $strDrinkThumb->hashName(),
            "price" => $request->price
        ]);

        return new DrinkResource(true, "Success",[]);
    }

    //destroy
    public function destroy($idDrink){
        $drink = Drink::where('idDrink', '=',$idDrink)->first();
        if (!$drink) {
            return response()->json(['message' => 'Drink Not Found'], 404);
        }else{
            Storage::delete('public/images/drinks/'.$drink->strDrinkThumb);
            $drink->delete();
            return new DrinkResource(true, 'Drink deleted',[]);
        }
    }
}

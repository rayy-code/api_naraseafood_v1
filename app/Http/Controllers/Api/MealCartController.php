<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealCartResource;
use App\Models\Meal;
use App\Models\MealCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Str;

class MealCartController extends Controller
{
    //mengambil semua data makanan dalam keranjang
    public function index()
    {
        $mealCart = MealCart::all();
        return new MealCartResource(true, 'All Data Meal Cart', [
            'meal_carts'=>$mealCart
        ]);
    }

    public function show($meal_cart)
    {
        $mealCarts = MealCart::where('idMealCart','=',$meal_cart)->get();
        foreach ($mealCarts as $value) {
            # code...
            $value->meal;
        }
        return new MealCartResource(true, "Cart $meal_cart", ['meal_carts'=>$mealCarts]);
    }

    //menambahkan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idMealCart'=>'required|string|uuid',
            'idMeal' => 'required|string|uuid',
            "qty" => "required|integer",
            'totalPrice'=>"required|integer"
        ]);
        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //baru di cek berdasarkan idMealCart, belum di cek berdasarkan idMeal
        //mengecek apakah data sudah ada atau belum
        $mealCartCheck = MealCart::where('idMealCart','=',$request->idMealCart)->where('idMeal','=',$request->idMeal)->first();
        
        //logic check
        if(isset($mealCartCheck))
        {
            //lakukan update
            $this->update($request, $request->idMealCart);
        }else{

            //membuat data baru
            $mealCart = MealCart::create([
                'idMealCart' => $request->idMealCart,
                'idMeal' => $request->idMeal,
                'qty' => $request->qty,
                "totalPrice" => $request->totalPrice
            ]);

            //mengembalikan informasi
            return new MealCartResource(true, "New Meal Cart Stored",[]);
        }

    }

    //mengupdate 
    public function update(Request $request, $meal_cart)
    {
        $validator = Validator::make($request->all(), [
            'idMealCart'=>'required|string|uuid',
            'idMeal' => 'required|string|uuid',
            "qty" => "required|integer",
            "totalPrice" => "required|integer"
        ]);
        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //mengambil data meal cart terpilih
        $MealCartSelect = MealCart::where('idMealCart','=',$meal_cart)->where('idMeal','=',$request->idMeal)->first();   

        //mengambil nilai qty terakhir
        $lastQty = $MealCartSelect->qty;    

        //mengambil data meal berdasarkan relasi
        $mealSelect = $MealCartSelect->meal;

        //menghitung totalPrice baru
        $newPrice = ($lastQty + 1) * $mealSelect->price;

        //mengupdate data meal cart
        $mealCartUpdate = MealCart::where('idMealCart','=',$meal_cart)->where('idMeal','=',$request->idMeal)->update([
            'qty' => $lastQty + 1,
            'totalPrice' => $newPrice
        ]);

        //memberikan informasi berhasil
        return new MealCartResource(true, "Data updated",[]);
    }

    //mengahpus 
    public function destroy($meal_cart)
    {
        $mealCart = MealCart::where('idMealCart',"=",$meal_cart)->delete();
        return new MealCartResource(true, "Data deleted", []);
    }

    public function delete($idMealCart, $idMeal)
    {
        $mealCart = MealCart::where('idMealCart','=',$idMealCart)->where('idMeal','=',$idMeal)->first();
        $qty = $mealCart->qty;
        $meal = $mealCart->meal;
        if($mealCart->qty === 1)
        {
            //$mealCart->delete();
            MealCart::where('idMealCart','=',$idMealCart)->where('idMeal','=',$idMeal)->delete();
        }else{
            $newQty = $qty-1;
            $newPrice = $newQty * $meal->price;
            MealCart::where('idMealCart','=',$idMealCart)->where('idMeal','=',$idMeal)->update([
                'qty' => $newQty,
                'totalPrice' => $newPrice
            ]);
            
        }
        return new MealCartResource(true, "Data deleted",[]);
    }
    

    //single value
    public function single($idMealCart, $idMeal)
    {
        $mealCart = MealCart::where('idMealCart','=',$idMealCart)->where('idMeal','=',$idMeal)->first();
        return new MealCartResource(true, "Data single",['meal_cart'=>$mealCart]);
    }
}

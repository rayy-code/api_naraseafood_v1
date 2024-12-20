<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrinkCart;
use App\http\Resources\DrinkCartResource;
use Illuminate\Support\Facades\Validator;

class DrinkCartController extends Controller
{
    //index
    public function index()
    {
        $drinkCart = DrinkCart::all();
        return new DrinkCartResource(true, "Data Drink Cart",['drink_carts'=> $drinkCart]);
    }

    //show
    public function show($idDrinkCart)
    {
        $drinkCart = DrinkCart::where('idDrinkCart','=',$idDrinkCart)->get();
        foreach($drinkCart as $key)
        {
            $key->drink;
        }
        return new DrinkCartResource(true, "Data Drink Cart",['drink_carts'=> $drinkCart]);
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idDrinkCart'=>'required|string|uuid',
            'idDrink' => 'required|string|uuid',
            "qty" => "required|integer",
            'totalPrice'=>"required|integer"
        ]);
        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //mengecek apakah data sudah ada atau belum
        $drinkCartCheck = DrinkCart::where('idDrinkCart','=',$request->idDrinkCart)->where('idDrink','=',$request->idDrink)->first();
        
        //logic check
        if(isset($drinkCartCheck))
        {
            //lakukan update
            $this->update($request, $request->idDrinkCart);
        }else{

            //membuat data baru
            $drinkCart = DrinkCart::create([
                'idDrinkCart' => $request->idDrinkCart,
                'idDrink' => $request->idDrink,
                'qty' => $request->qty,
                "totalPrice" => $request->totalPrice
            ]);

            //mengembalikan informasi
            return new DrinkCartResource(true, "New Drink Cart Stored",[]);
        }
    }

    //update
    public function update(Request $request, $idDrinkCart)
    {
        $validator = Validator::make($request->all(), [
            'idDrinkCart'=>'required|string|uuid',
            'idDrink' => 'required|string|uuid',
            "qty" => "required|integer",
            "totalPrice" => "required|integer"
        ]);
        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //mengambil data meal cart terpilih
        $drinkCartSelect = DrinkCart::where('idDrinkCart','=',$idDrinkCart)->where('idDrink','=',$request->idDrink)->first();   

        //mengambil nilai qty terakhir
        $lastQty = $drinkCartSelect->qty;    

        //mengambil data meal berdasarkan relasi
        $drinkSelect = $drinkCartSelect->drink;

        //menghitung totalPrice baru
        $newPrice = ($lastQty + 1) * $drinkSelect->price;

        //mengupdate data meal cart
        $drinkCartUpdate = DrinkCart::where('idDrinkCart','=',$idDrinkCart)->where('idDrink','=',$request->idDrink)->update([
            'qty' => $lastQty + 1,
            'totalPrice' => $newPrice
        ]);

        //memberikan informasi berhasil
        return new DrinkCartResource(true, "Data updated",[]);
    }

    //delete
    public function delete($idDrinkCart, $idDrink)
    {
        $drinkCart = DrinkCart::where('idDrinkCart','=',$idDrinkCart)->where('idDrink','=',$idDrink)->first();
        $qty = $drinkCart->qty;
        $drink = $drinkCart->drink;
        if($drinkCart->qty ===1)
        {
            DrinkCart::where('idDrinkCart','=',$idDrinkCart)->where('idDrink','=',$idDrink)->delete();
        }else{
            $newQty = $qty-1;
            $newPrice = $newQty * $drink->price;
            DrinkCart::where('idDrinkCart','=',$idDrinkCart)->where('idDrink','=',$idDrink)->update([
                'qty' => $newQty,
                'totalPrice' => $newPrice
            ]);
        }
        return new DrinkCartResource(true, "Data deleted",[]);
    }
}

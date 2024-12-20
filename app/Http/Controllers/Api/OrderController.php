<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\DrinkCart;
use App\Models\MealCart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //index
    public function index()
    {
        $orders = Order::all();
        return new OrderResource(true, "Order Data",["orders"=>$orders]);
    }

    //show
    public function show($idOrder)
    {
        $order = Order::where('idOrder','=',$idOrder)->first();
        if($order){
            $meals = $order->meal_carts;
            $drinks = $order->drink_carts;
            foreach($meals as $value)
            {
                $value->meal;
            }
            foreach($drinks as $value)
            {
                $value->drink;
            }
            return new OrderResource(true, "Order Data",["order"=>$order]);
        }else{
            return new OrderResource(false, "Order Not Found",[]);
        }
    }

    //store
    public function store(Request $request)
    {
        //blm di tes
        $validator = Validator::make($request->all(),[
            'idOrder'=> 'required|uuid',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $totalPrice = $this->getTotalOrder($request->idMealCart, $request->idDrinkCart);

        $order = Order::create([
            'idOrder' => $request->idOrder,
            'idMealCart' => $request->idMealCart,
            'idDrinkCart'=>$request->idDrinkCart,
            'total_price' => $totalPrice
        ]);

        return new OrderResource(true, "Data stored",[]);

    }

    public function getTotalOrder($idMealCart, $idDrinkCart)
    {
        $mealOrder = MealCart::where('idMealCart','=',$idMealCart)->get();
        $drinkOrder = DrinkCart::where('idDrinkCart','=',$idDrinkCart)->get();
        $totalPrice = 0;
        foreach($mealOrder as $value)
        {
            $totalPrice += $value->totalPrice;
        }
        foreach($drinkOrder as $value)
        {
            $totalPrice += $value->totalPrice;
        }

        return $totalPrice;
        //return new OrderResource(true, "Total Price",['totalPrice'=>$totalPrice]);
    }
}

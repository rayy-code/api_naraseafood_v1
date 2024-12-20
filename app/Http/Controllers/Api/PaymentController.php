<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    //index
    public function index()
    {
        $payment = Payment::all();
        return new  PaymentResource(true, "Data payment", ['payment'=>$payment]);
    }

    //show
    public function show($idPayment)
    {
        $payment = Payment::where('idPayment','=',$idPayment)->first();
        $order = $payment->order;
        $meal = $order->meal_carts;
        $drink = $order->drink_carts;
        foreach($meal as $value)
        {
            $value->meal;
        }
        foreach($drink as $value)
        {
            $value->drink;
        }
        return new PaymentResource(true, "Data Payment", ['payment'=>$payment]);
    }

    //store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'idOrder'=>'required|uuid|exists:orders,idOrder',
            'total_price' => 'required|integer',
            'pay' => 'required|integer'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $excessMoney = $request->pay - $request->total_price;

        $payment = Payment::create([
            'idPayment' => Str::uuid(),
            'idOrder' => $request->idOrder,
            'total_price' => $request->total_price,
            'pay' => $request->pay,
            'excessMoney' => $excessMoney
        ]);

        return new PaymentResource(true, 'Data stored',['excessMoney'=>$excessMoney]);
    }

    public function sales($date)
    {
        $sales = Payment::whereDate('created_at','LIKE',$date.'%')->get();
        return new PaymentResource(true, "Sales ".$date, ['sales'=>$sales]);
    }


}

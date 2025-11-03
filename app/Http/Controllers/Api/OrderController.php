<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'success' => true,
            'message' => 'All Order',
            'data' => [
                'orders' => $orders
            ]
        ], 200);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $orderShow = $order->loadMissing('orderDetail:order_id,price,item_id', 'orderDetail.item:id,name', 'waitress:id,name', 'cashier:id,name');
         return response()->json([
            'success' => true,
            'data' => [
                'orderShow' => $orderShow
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:100',
            'table_no' => 'required|max:5',
        ]);

        try {
            DB::beginTransaction();
            $data = $request->only(['customer_name', 'table_no']);
            // return $data

            // buat order_date
            $data['order_date'] = date('Y-m-d');

            // buat order time
            $data['order_time'] = date('H:i:s');

            // default status
            $data['status'] = 'ordered';

            // total
            $data['total'] = 0;

            // waitres_id
            $data['waitress_id'] = Auth::user()->id;

            // items
            $data['items'] = $request->items;

            $order = Order::create($data);
            
            DB::commit();

            // masuk ke table order details
            collect($data['items'])->map(function($item)use($order){
                $foodDrink = Item::where('id', $item)->first();
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item,
                    'price' => $foodDrink->price
                ]);
            });

            // edit total order
            $order->total = $order->sumOrderPrice();
            $order->save();

        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th);
        }

        return response()->json([
            'success' => true,
            'message' => 'Create Order successful',
            'data' => [
                'order' => $order
            ]
        ], 200);
    }

    public function setAsDone($id)
    {
        $order = Order::findOrFail($id);
        if($order->status != "ordered")
        {
              return response()->json([
                'success' => false,
                'message' => 'order cannot set to done because the status is not ordered'
            ], 403);
        } 

        $order->status = "done";
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status successfully changed to done',
            'data' => [
                'orderStatus' => $order
            ]
        ], 200);
        
    }

    public function payment($id)
    {
        $order = Order::findOrFail($id);
        if($order->status != "done")
        {
              return response()->json([
                'success' => false,
                'message' => 'order cannot set to payment because the status is not done'
            ], 403);
        } 

        $order->status = "paid";
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status successfully changed to paid',
            'data' => [
                'orderStatus' => $order
            ]
        ], 200);
        
    }
    
}

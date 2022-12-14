<?php

namespace App\Respositories;

use App\Models\Order;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class OrderRepository{

    /**
     * @param $request
     * @description This function is used to handle parameters from request for creating new order.
     * @return mixed
     */
    public function filterParams($request){
        $userId = $request->user()->id;
        $itemsOrder = $request->items_order;
        foreach($itemsOrder as $key => $item){
            $finalPrice = Book::finalPrice($item['book_id']);
            $itemsOrder[$key]['price'] = $finalPrice;
        }
        return [$userId, $itemsOrder];
    }

    /**
     * @param $userId
     * @param $itemsOrder
     * @description Create order and order items
     * @return Order
     */
    public function createOrder($userId, $itemsOrder){
        DB::beginTransaction();
        try{
            $order = Order::create([
                'user_id' => $userId,
                'order_date' => Carbon::now(),
                'order_amount' => count($itemsOrder),
            ]);
            $order->items()->createMany($itemsOrder);
            DB::commit();
            return $order;
        }catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
    }       
}
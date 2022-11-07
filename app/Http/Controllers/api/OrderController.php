<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostOrderRequest;
use Illuminate\Http\Request;
use App\Respositories\OrderRepository;

class OrderController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        //
    }
    
    public function store(PostOrderRequest $request)
    {
        $params = $this->orderRepository->filterParams($request);
        $order = $this->orderRepository->createOrder(...$params);
        
        $order['status'] = 201;

        return response()->json($order, 201);
    }
}

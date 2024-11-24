<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::all();

        return view('order.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        return view('order.show', compact('order'));
    }
}

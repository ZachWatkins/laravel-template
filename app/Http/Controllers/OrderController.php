<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::all();

        return view('order.index', compact('orders'));
    }

    public function show(Request $request, Order $order): Response
    {
        return view('order.show', compact('order'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::all();

        return view('order.index', compact('orders'));
    }

    public function store(OrderStoreRequest $request): RedirectResponse
    {
        $order = Order::create($request->validated());

        $request->session()->flash('order.id', $order->id);

        return redirect()->route('orders.index');
    }

    public function show(Request $request, Order $order): View
    {
        return view('order.show', compact('order'));
    }
}

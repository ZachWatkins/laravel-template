<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ShoppingCartController extends Controller
{
    public function order(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required|exists:shopping_carts,id',
        ]);

        $shoppingCart = ShoppingCart::find($validated['id']);

        if (!$shoppingCart->orderItems->count()) {
            return redirect()->route('shopping-carts.show', ['shopping_cart' => $shoppingCart->id]);
        }

        $order = Order::create($shoppingCart->only([
            'customer_id',
            'total',
            'number',
        ]));

        $order->orderItems()->sync($shoppingCart->orderItems);
        $shoppingCart->delete();
        return redirect()->route('orders.show', ['order' => $order->id]);
    }
}

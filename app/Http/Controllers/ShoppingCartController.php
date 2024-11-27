<?php

namespace App\Http\Controllers;

use App\CustomerId;
use App\Http\Requests\ShoppingCartAddItemRequest;
use App\Http\Requests\ShoppingCartRemoveItemRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoppingCartController extends Controller
{
    public function addItem(ShoppingCartAddItemRequest $request): RedirectResponse
    {
        $request->session()->flash('quantity', $quantity);
    }

    public function removeItem(ShoppingCartRemoveItemRequest $request): RedirectResponse
    {
        $request->session()->flash('quantity', $quantity);
    }

    public function empty(Request $request): RedirectResponse
    {
        $request->session()->flash('empty', $empty);
    }

    public function order(Request $request): RedirectResponse
    {
        $customerId = CustomerId::find($customer_id);

        return redirect()->route('orders.show', [$order]);
    }
}

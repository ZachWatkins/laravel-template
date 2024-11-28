<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingCartItemStoreRequest;
use App\Http\Requests\ShoppingCartItemUpdateRequest;
use App\Models\ShoppingCartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShoppingCartItemController extends Controller
{
    public function index(Request $request): View
    {
        $shoppingCartItems = ShoppingCartItem::all();

        return view('shoppingCartItem.index', compact('shoppingCartItems'));
    }

    public function create(Request $request): View
    {
        return view('shoppingCartItem.create');
    }

    public function store(ShoppingCartItemStoreRequest $request): RedirectResponse
    {
        $shoppingCartItem = ShoppingCartItem::create($request->validated());

        $request->session()->flash('shoppingCartItem.id', $shoppingCartItem->id);

        return redirect()->route('shopping-cart-items.index');
    }

    public function show(Request $request, ShoppingCartItem $shoppingCartItem): View
    {
        return view('shoppingCartItem.show', compact('shoppingCartItem'));
    }

    public function edit(Request $request, ShoppingCartItem $shoppingCartItem): View
    {
        return view('shoppingCartItem.edit', compact('shoppingCartItem'));
    }

    public function update(ShoppingCartItemUpdateRequest $request, ShoppingCartItem $shoppingCartItem): RedirectResponse
    {
        $shoppingCartItem->update($request->validated());

        $request->session()->flash('shoppingCartItem.id', $shoppingCartItem->id);

        return redirect()->route('shopping-cart-items.index');
    }

    public function destroy(Request $request, ShoppingCartItem $shoppingCartItem): RedirectResponse
    {
        $shoppingCartItem->delete();

        return redirect()->route('shopping-cart-items.index');
    }
}

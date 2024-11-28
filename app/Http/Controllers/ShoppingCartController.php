<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingCartStoreRequest;
use App\Http\Requests\ShoppingCartUpdateRequest;
use App\Models\ShoppingCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShoppingCartController extends Controller
{
    public function index(Request $request): View
    {
        $shoppingCarts = ShoppingCart::all();

        return view('shoppingCart.index', compact('shoppingCarts'));
    }

    public function create(Request $request): View
    {
        return view('shoppingCart.create');
    }

    public function store(ShoppingCartStoreRequest $request): RedirectResponse
    {
        $shoppingCart = ShoppingCart::create($request->validated());

        $request->session()->flash('shoppingCart.id', $shoppingCart->id);

        return redirect()->route('shopping-carts.index');
    }

    public function show(Request $request, ShoppingCart $shoppingCart): View
    {
        return view('shoppingCart.show', compact('shoppingCart'));
    }

    public function edit(Request $request, ShoppingCart $shoppingCart): View
    {
        return view('shoppingCart.edit', compact('shoppingCart'));
    }

    public function update(ShoppingCartUpdateRequest $request, ShoppingCart $shoppingCart): RedirectResponse
    {
        $shoppingCart->update($request->validated());

        $request->session()->flash('shoppingCart.id', $shoppingCart->id);

        return redirect()->route('shopping-carts.index');
    }

    public function destroy(Request $request, ShoppingCart $shoppingCart): RedirectResponse
    {
        $shoppingCart->delete();

        return redirect()->route('shopping-carts.index');
    }
}

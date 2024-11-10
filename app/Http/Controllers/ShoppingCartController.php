<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoppingCartController extends Controller
{
    public function order(Request $request): Response
    {
        $shoppingCart = ShoppingCart::find($id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerPaymentMethodStoreRequest;
use App\Http\Requests\CustomerPaymentMethodUpdateRequest;
use App\Models\CustomerPaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPaymentMethodController extends Controller
{
    public function index(Request $request): View
    {
        $customerPaymentMethods = CustomerPaymentMethod::all();

        return view('customerPaymentMethod.index', compact('customerPaymentMethods'));
    }

    public function create(Request $request): View
    {
        return view('customerPaymentMethod.create');
    }

    public function store(CustomerPaymentMethodStoreRequest $request): RedirectResponse
    {
        $customerPaymentMethod = CustomerPaymentMethod::create($request->validated());

        $request->session()->flash('customerPaymentMethod.id', $customerPaymentMethod->id);

        return redirect()->route('customer-payment-methods.index');
    }

    public function show(Request $request, CustomerPaymentMethod $customerPaymentMethod): View
    {
        return view('customerPaymentMethod.show', compact('customerPaymentMethod'));
    }

    public function edit(Request $request, CustomerPaymentMethod $customerPaymentMethod): View
    {
        return view('customerPaymentMethod.edit', compact('customerPaymentMethod'));
    }

    public function update(CustomerPaymentMethodUpdateRequest $request, CustomerPaymentMethod $customerPaymentMethod): RedirectResponse
    {
        $customerPaymentMethod->update($request->validated());

        $request->session()->flash('customerPaymentMethod.id', $customerPaymentMethod->id);

        return redirect()->route('customer-payment-methods.index');
    }

    public function destroy(Request $request, CustomerPaymentMethod $customerPaymentMethod): RedirectResponse
    {
        $customerPaymentMethod->delete();

        return redirect()->route('customer-payment-methods.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        $customers = Customer::all();

        return view('customer.index', compact('customers'));
    }

    public function create(Request $request): Response
    {
        return view('customer.create');
    }

    public function store(CustomerStoreRequest $request): Response
    {
        $customer = Customer::create($request->validated());

        $request->session()->flash('customer.id', $customer->id);

        return redirect()->route('customers.index');
    }

    public function show(Request $request, Customer $customer): Response
    {
        return view('customer.show', compact('customer'));
    }

    public function edit(Request $request, Customer $customer): Response
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): Response
    {
        $customer->update($request->validated());

        $request->session()->flash('customer.id', $customer->id);

        return redirect()->route('customers.index');
    }

    public function destroy(Request $request, Customer $customer): Response
    {
        $customer->delete();

        return redirect()->route('customers.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartWebpageStoreRequest;
use App\Http\Requests\PartWebpageUpdateRequest;
use App\Models\PartWebpage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartWebpageController extends Controller
{
    public function index(Request $request): Response
    {
        $partWebpages = PartWebpage::all();

        return view('partWebpage.index', compact('partWebpages'));
    }

    public function create(Request $request): Response
    {
        return view('partWebpage.create');
    }

    public function store(PartWebpageStoreRequest $request): Response
    {
        $partWebpage = PartWebpage::create($request->validated());

        $request->session()->flash('partWebpage.id', $partWebpage->id);

        return redirect()->route('partWebpages.index');
    }

    public function show(Request $request, PartWebpage $partWebpage): Response
    {
        return view('partWebpage.show', compact('partWebpage'));
    }

    public function edit(Request $request, PartWebpage $partWebpage): Response
    {
        return view('partWebpage.edit', compact('partWebpage'));
    }

    public function update(PartWebpageUpdateRequest $request, PartWebpage $partWebpage): Response
    {
        $partWebpage->update($request->validated());

        $request->session()->flash('partWebpage.id', $partWebpage->id);

        return redirect()->route('partWebpages.index');
    }

    public function destroy(Request $request, PartWebpage $partWebpage): Response
    {
        $partWebpage->delete();

        return redirect()->route('partWebpages.index');
    }
}

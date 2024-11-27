<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartStoreRequest;
use App\Http\Requests\PartUpdateRequest;
use App\Models\Part;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartController extends Controller
{
    public function index(Request $request): View
    {
        $parts = Part::all();

        return view('part.index', compact('parts'));
    }

    public function create(Request $request): View
    {
        return view('part.create');
    }

    public function store(PartStoreRequest $request): RedirectResponse
    {
        $part = Part::create($request->validated());

        $request->session()->flash('part.id', $part->id);

        return redirect()->route('parts.index');
    }

    public function show(Request $request, Part $part): View
    {
        return view('part.show', compact('part'));
    }

    public function edit(Request $request, Part $part): View
    {
        return view('part.edit', compact('part'));
    }

    public function update(PartUpdateRequest $request, Part $part): RedirectResponse
    {
        $part->update($request->validated());

        $request->session()->flash('part.id', $part->id);

        return redirect()->route('parts.index');
    }

    public function destroy(Request $request, Part $part): RedirectResponse
    {
        $part->delete();

        return redirect()->route('parts.index');
    }
}

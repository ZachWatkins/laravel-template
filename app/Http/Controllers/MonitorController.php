<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorStoreRequest;
use App\Http\Requests\MonitorUpdateRequest;
use App\Models\Monitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitorController extends Controller
{
    public function index(Request $request): Response
    {
        $monitors = Monitor::all();

        return view('monitor.index', compact('monitors'));
    }

    public function create(Request $request): Response
    {
        return view('monitor.create');
    }

    public function store(MonitorStoreRequest $request): Response
    {
        $monitor = Monitor::create($request->validated());

        $request->session()->flash('monitor.id', $monitor->id);

        return redirect()->route('monitors.index');
    }

    public function show(Request $request, Monitor $monitor): Response
    {
        return view('monitor.show', compact('monitor'));
    }

    public function edit(Request $request, Monitor $monitor): Response
    {
        return view('monitor.edit', compact('monitor'));
    }

    public function update(MonitorUpdateRequest $request, Monitor $monitor): Response
    {
        $monitor->update($request->validated());

        $request->session()->flash('monitor.id', $monitor->id);

        return redirect()->route('monitors.index');
    }

    public function destroy(Request $request, Monitor $monitor): Response
    {
        $monitor->delete();

        return redirect()->route('monitors.index');
    }
}

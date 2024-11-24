<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorSubscriberStoreRequest;
use App\Http\Requests\MonitorSubscriberUpdateRequest;
use App\Models\MonitorSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitorSubscriberController extends Controller
{
    public function index(Request $request): View
    {
        $monitorSubscribers = MonitorSubscriber::all();

        return view('monitorSubscriber.index', compact('monitorSubscribers'));
    }

    public function create(Request $request): View
    {
        return view('monitorSubscriber.create');
    }

    public function store(MonitorSubscriberStoreRequest $request): RedirectResponse
    {
        $monitorSubscriber = MonitorSubscriber::create($request->validated());

        $request->session()->flash('monitorSubscriber.id', $monitorSubscriber->id);

        return redirect()->route('monitor-subscribers.index');
    }

    public function show(Request $request, MonitorSubscriber $monitorSubscriber): View
    {
        return view('monitorSubscriber.show', compact('monitorSubscriber'));
    }

    public function edit(Request $request, MonitorSubscriber $monitorSubscriber): View
    {
        return view('monitorSubscriber.edit', compact('monitorSubscriber'));
    }

    public function update(MonitorSubscriberUpdateRequest $request, MonitorSubscriber $monitorSubscriber): RedirectResponse
    {
        $monitorSubscriber->update($request->validated());

        $request->session()->flash('monitorSubscriber.id', $monitorSubscriber->id);

        return redirect()->route('monitor-subscribers.index');
    }

    public function destroy(Request $request, MonitorSubscriber $monitorSubscriber): RedirectResponse
    {
        $monitorSubscriber->delete();

        return redirect()->route('monitor-subscribers.index');
    }
}

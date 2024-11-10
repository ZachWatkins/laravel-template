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
    public function index(Request $request): Response
    {
        $monitorSubscribers = MonitorSubscriber::all();

        return view('monitorSubscriber.index', compact('monitorSubscribers'));
    }

    public function create(Request $request): Response
    {
        return view('monitorSubscriber.create');
    }

    public function store(MonitorSubscriberStoreRequest $request): Response
    {
        $monitorSubscriber = MonitorSubscriber::create($request->validated());

        $request->session()->flash('monitorSubscriber.id', $monitorSubscriber->id);

        return redirect()->route('monitorSubscribers.index');
    }

    public function show(Request $request, MonitorSubscriber $monitorSubscriber): Response
    {
        return view('monitorSubscriber.show', compact('monitorSubscriber'));
    }

    public function edit(Request $request, MonitorSubscriber $monitorSubscriber): Response
    {
        return view('monitorSubscriber.edit', compact('monitorSubscriber'));
    }

    public function update(MonitorSubscriberUpdateRequest $request, MonitorSubscriber $monitorSubscriber): Response
    {
        $monitorSubscriber->update($request->validated());

        $request->session()->flash('monitorSubscriber.id', $monitorSubscriber->id);

        return redirect()->route('monitorSubscribers.index');
    }

    public function destroy(Request $request, MonitorSubscriber $monitorSubscriber): Response
    {
        $monitorSubscriber->delete();

        return redirect()->route('monitorSubscribers.index');
    }
}

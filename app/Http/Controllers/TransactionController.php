<?php

namespace App\Http\Controllers;

use App\Models\Event\Event;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('sherehe.dash.transactions.index', compact('events'));
    }

    // public function events()
    // {
    //     return view('sherehe.dash.transactions.tabs.event');
    // }

    public function sms()
    {
        return view('sherehe.dash.transactions.tabs.sms');
    }

    public function storeInitial(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'initial_payment' => 'required|numeric|min:0',
        ]);

        $event = Event::findOrFail($request->event_id);
        $event->initial_payment = $request->initial_payment;
        $event->save();

        return back()->with('success', 'Initial payment for ' . $event->event_name . ' saved successfully.');
    }

    public function storeFinal(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'final_payment' => 'required|numeric|min:0',
        ]);

        $event = Event::findOrFail($request->event_id);
        $event->final_payment = $request->final_payment;
        $event->save();

        return back()->with('success', 'Final payment for ' . $event->event_name . ' saved successfully.');
    }
}

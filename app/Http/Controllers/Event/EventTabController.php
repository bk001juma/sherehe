<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventAttendee;
use App\Models\Event\EventAttendeesCategory;
use App\Models\Event\EventCardType;
use App\Models\Event\ItemType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventTabController extends Controller
{
    public function items($id)
    {
        //        $pdf = Pdf::loadView('sherehe.dash.event.cards.a');
        //        return $pdf->setPaper('a5')->stream('invoice.pdf');

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_items', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function pledgerCategories($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_pledger_categories', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function pledges($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_pledges', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function pledgesLink($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_pledge_link', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function pledgesName($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_pledge_with_name', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function cards($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $users = User::orderBy('id', 'desc')->get();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_cards', compact('event', 'item_types', 'event_attendees_categories', 'user', 'users'));
        }
    }

    public function paidTickets($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_paid_ticket', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function tickets($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_tickets', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }


    public function smsNotifications($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_sms_notifications', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function whatsappNotifications($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_whatsapp_notification', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function cardNamePosition($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_position_pledge_name', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function cardLinkPosition($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_position_pledge_name', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function ticketNamePosition($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_position_pledge_ticket', compact('event', 'item_types', 'event_attendees_categories', 'user'));
        }
    }

    public function report($id)
    {

        $user = Auth::user();
        if ($user->hasRole('admin') || isset($user->events()->where('id', $id)->first()->id)) {
            $event = Event::find($id);
        } else {
            return redirect()->route('dash.events');

            abort(403, 'Unauthorized action.');
        }
        $item_types = ItemType::all();
        $card_types  = $event->card_types;
        $event_attendess = collect();

        if (!isset($card_types->id)) {
            $event_crd = new EventCardType();
            $event->card_types()->save($event_crd);
        }
        $event_attendees_categories = EventAttendeesCategory::where('event_id', $event->id)->get();

        if ($event->status != 'active') {
            return redirect()->route('dash.event.order', $event->id);
        } else {
            //            return redirect()->route('dash.event',$event->id);
            return view('sherehe.dash.event.tabs.show_event_report', compact('event', 'item_types', 'event_attendees_categories', 'card_types', 'user', 'event_attendess'));
        }
    }

    public function reportFilter(Request $request, $id)
    {
        $event = Event::with('card_types')->findOrFail($id);

        // Check if card types are available before accessing them
        $singleAmount = optional($event->card_types)->single_amount;
        $doubleAmount = optional($event->card_types)->double_amount;

        $event_attendees = EventAttendee::where('event_id', $event->id)
            ->when($request->filled('event_attendees_category_id'), function ($query) use ($request) {
                return $query->where('event_attendees_category_id', $request->input('event_attendees_category_id'));
            })
            ->when($request->filled('card_type'), function ($query) use ($request, $singleAmount, $doubleAmount) {
                if ($request->card_type == 'single' && $singleAmount !== null && $doubleAmount !== null) {
                    return $query->where('paid', '>=', $singleAmount)
                        ->where('paid', '<', $doubleAmount);
                } elseif ($request->card_type == 'double' && $doubleAmount !== null) {
                    return $query->where('paid', '>=', $doubleAmount);
                } elseif ($request->card_type == 'incomplete' && $singleAmount !== null) {
                    return $query->where('paid', '>=', 0)
                        ->where('paid', '<', $singleAmount);
                } elseif ($request->card_type == 'not_paid') {
                    return $query->where('paid', 0);
                } elseif ($request->card_type == 'partial_paid') {
                    return $query->where('paid', '>', 0)
                        ->where('paid', '<', $singleAmount);
                }
            })
            ->when($request->filled('is_attending'), function ($query) use ($request) {
                return $query->where('is_attending', $request->is_attending);
            })
            ->when($request->filled('card_received'), function ($query) use ($request) {
                return $query->where('card_received', $request->card_received);
            })
            // ->with('category')
            ->get()->map(function ($attendee) use ($singleAmount, $doubleAmount) {
                $cardType = null;
                if ($attendee->paid >= $doubleAmount) {
                    $cardType = 'Double';
                } elseif ($attendee->paid >= $singleAmount) {
                    $cardType = 'Single';
                }

                return [
                    ...$attendee->toArray(),
                    'card_type' => $cardType,
                    'category_name' => optional($attendee->category)->name,
                ];
            });

        return view('sherehe.dash.event.tabs.show_event_report', [
            'event' => $event,
            'item_types' => ItemType::all(),
            'card_types' => $event->card_types,
            'event_attendees_categories' => EventAttendeesCategory::where('event_id', $event->id)->get(),
            'event_attendess' => $event_attendees,
            'user' => Auth::user(),
        ]);
    }
}

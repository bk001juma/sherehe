<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventAttendee;
use App\Models\Event\EventAttendeesCategory;
use Illuminate\Http\Request;

class AttendeeResponseController extends Controller
{
    // Show the response form
    public function showResponseForm($event_id, $attendee_id)
    {
        $event = Event::findOrFail($event_id);
        $attendee = EventAttendeesCategory::findOrFail($attendee_id);

        return view('sherehe.attendee.response_form', compact('event', 'attendee'));
    }

    // Handle form submission
    // public function submitResponse(Request $request)
    // {
    //     $request->validate([
    //         'event_id' => 'required|exists:events,id',
    //         'attendee_id' => 'required|exists:event_attendees,id',
    //         'response' => 'required|in:yes,no',
    //     ]);

    //     $attendee = EventAttendee::findOrFail($request->attendee_id);

    //     // Update attendee's response status
    //     $attendee->attendee_response = $request->response === 'yes';
    //     $attendee->save();

    //     return redirect()->route('attendee.response.form', [
    //         'event_id' => $request->event_id,
    //         'attendee_id' => $attendee->id,
    //     ])->with('success', 'Asante kwa kuthibitisha kushiriki kwako!');
    // }

    public function submitResponse($event_id, $attendee_id, Request $request)
    {
        $attendee = EventAttendee::find($attendee_id);
        if (!$attendee) {
            return redirect()->back()->with('error', 'Attendee not found.');
        }

        // Update response based on the clicked option
        $attendee->attending_response = $request->get('response');
        $attendee->save();

        return view('sherehe.thank_you_popup', [
            'message' => 'Thank you for your response!',
        ]);
    }

    public function attendYes($attendee_id)
    {
        // Logic to record "I'll Attend" response
        // Example: Update database to mark attendee as attending
        return response()->json([
            'message' => 'Thank you for confirming your attendance!',
            'attendee_id' => $attendee_id,
            'response' => 'yes'
        ]);
    }

    public function attendNo($attendee_id)
    {
        // Logic to record "Can't Make It" response
        // Example: Update database to mark attendee as not attending
        return response()->json([
            'message' => 'Sorry you can\'t make it!',
            'attendee_id' => $attendee_id,
            'response' => 'no'
        ]);
    }
}

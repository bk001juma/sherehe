<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Imports\PledgesImport;
use App\Models\Event\EventAttendee;
use App\Models\Event\EventAttendeesCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendeeController extends Controller
{
    public function addPledge(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                Excel::import(new PledgesImport($request['event_id'], $request['event_attendees_category_id']), $request['file']);

                return redirect()->back()->with('success', 'Pledges imported successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to import pledges: ' . $e->getMessage());
            }
        }

        $request->validate([
            'event_id' => 'required',
            'full_name' => 'required',
            'phone' => 'required',
            'amount' => 'required',
            'event_attendees_category_id' => 'nullable',
            'table_number' => 'nullable|string|max:255',
        ]);

        EventAttendee::create($request->all());

        return redirect()->back()->with('success', 'Pledge added successfully.');
    }

    public function addPledgeCategory(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'event_id' => 'required',
        ]);

        EventAttendeesCategory::create($request->all());

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'amount' => 'required',
            'event_attendees_category_id' => 'nullable',
            'table_number' => 'nullable|string|max:255',
        ]);

        $item = EventAttendee::find($request['id']);

        $item->update($request->all());

        return redirect()->back();
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $item = EventAttendeesCategory::find($request['id']);

        $item->update($request->all());

        return redirect()->back();
    }

    public function addPayment(Request $request, $id)
    {
        $request->validate([
            'method' => 'required',
            'amount' => 'required|int',
        ]);

        $attendee = EventAttendee::find($id);
        $attendee->payments()->create($request->all());

        $attendee->paid = $attendee->payments->sum('amount');
        $attendee->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        EventAttendee::destroy($id);

        return redirect()->back();
    }
    public function destroyCategory($id)
    {
        EventAttendeesCategory::destroy($id);

        return redirect()->back();
    }
}

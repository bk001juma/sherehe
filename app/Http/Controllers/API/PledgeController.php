<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\PledgesImport;
use App\Models\Event\EventAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PledgeController extends Controller
{
    public function addPledge(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                Excel::import(new PledgesImport(
                    $request->input('event_id'),
                    $request->input('event_attendees_category_id')
                ), $request->file('file'));

                return response()->json([
                    'success' => true,
                    'message' => 'Pledges imported successfully.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to import pledges: ' . $e->getMessage(),
                ], 500);
            }
        }

        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'full_name' => 'required',
            'phone' => 'required',
            'amount' => 'required',
            'event_attendees_category_id' => 'nullable',
            'table_number' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pledge = EventAttendee::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pledge added successfully.',
            'data' => $pledge,
        ]);
    }

    public function listPledges($eventId)
    {
        $pledges = EventAttendee::where('event_id', $eventId)->orderByDesc('id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Pledges retrieved successfully.',
            'data' => $pledges
        ]);
    }

    public function getPledge($id)
    {
        $pledge = EventAttendee::find($id);

        if (!$pledge) {
            return response()->json([
                'success' => false,
                'message' => 'Pledge not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pledge found',
            'data' => $pledge
        ]);
    }

    public function updatePledge(Request $request, $id)
    {
        $pledge = EventAttendee::find($id);

        if (!$pledge) {
            return response()->json([
                'success' => false,
                'message' => 'Pledge not found.'
            ], 404);
        }

        $request->validate([
            'event_id' => 'required',
            'full_name' => 'required',
            'phone' => 'required',
            'amount' => 'required',
            'event_attendees_category_id' => 'nullable',
            'table_number' => 'nullable',
        ]);

        $pledge->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pledge updated successfully.',
            'data' => $pledge
        ]);
    }

    public function deletePledge($id)
    {
        $pledge = EventAttendee::find($id);

        if (!$pledge) {
            return response()->json([
                'success' => false,
                'message' => 'Pledge not found.'
            ], 404);
        }

        $pledge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pledge deleted successfully.'
        ]);
    }
}

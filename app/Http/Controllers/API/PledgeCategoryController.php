<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\EventAttendeesCategory;
use Illuminate\Http\Request;

class PledgeCategoryController extends Controller
{
    public function index($eventId)
    {
        $categories = EventAttendeesCategory::where('event_id',$eventId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Pledge categories retrieved successfully.',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'event_id' => 'required',
        ]);

        $category = EventAttendeesCategory::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pledge category created successfully.',
            'data' => $category
        ]);
    }

    public function show($id)
    {
        $category = EventAttendeesCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Pledge category not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = EventAttendeesCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Pledge category not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required',
            'event_id' => 'required',
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pledge category updated successfully.',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = EventAttendeesCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Pledge category not found.'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pledge category deleted successfully.'
        ]);
    }
}

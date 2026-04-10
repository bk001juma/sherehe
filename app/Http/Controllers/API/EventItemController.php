<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\EventItem;
use Illuminate\Http\Request;

class EventItemController extends Controller
{
    // ✅ List all items for specific event_id
    public function index($event_id)
    {
        $items = EventItem::where('event_id', $event_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Event items fetched successfully.',
            'data' => $items,
        ]);
    }

    // ✅ Store new event item
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'item_type_id' => 'required|integer',
            'name' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $item = EventItem::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Event item created successfully.',
            'data' => $item,
        ]);
    }

    // ✅ Update an existing item
    public function update(Request $request, $id)
    {
        $item = EventItem::findOrFail($id);

        $request->validate([
            'item_type_id' => 'sometimes|integer',
            'name' => 'sometimes|string',
            'amount' => 'sometimes|numeric',
        ]);

        $item->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Event item updated successfully.',
            'data' => $item,
        ]);
    }

    // ✅ Delete an item
    public function destroy($id)
    {
        $item = EventItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event item deleted successfully.',
        ]);
    }
}

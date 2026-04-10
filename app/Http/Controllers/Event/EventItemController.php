<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\EventItem;
use App\Models\Event\ItemPayment;
use Illuminate\Http\Request;

class EventItemController extends Controller
{
    public function addItem(Request $request)
    {
        $request->validate([
            'event_id'=> 'required',
            'item_type_id'=> 'required',
            'name'=> 'required',
            'amount'=> 'required',
        ]);

        EventItem::create($request->all());

        return redirect()->back();
    }

    public function updateItem(Request $request,$id)
    {
        $request->validate([
            'item_type_id'=> 'required',
            'name'=> 'required',
            'amount'=> 'required',
        ]);

        $item = EventItem::find($id);

        $item->update($request->except('id'));

        return redirect()->back();
    }

    public function destroyItem($id)
    {
        EventItem::destroy($id);

        return redirect()->back();
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'event_item_id'=> 'required',
            'method'=> 'required',
            'amount'=> 'required|int',
        ]);

        $item = EventItem::find($request['event_item_id']);
        $item->payments()->create($request->all());

        $item->paid = $item->payments->sum('amount');
        $item->save();

        return redirect()->back();
    }
}

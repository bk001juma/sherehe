<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\BulkSMS;
use App\Models\Event\Payment;
use App\Models\Event\WhatsappSms;
use App\Traits\PhoneNumberTrait;
use Illuminate\Http\Request;

class BulkSMSController extends Controller
{
    public function createOrder(Request $request)
    {
        $request['amount'] = $request['sms_count'] * 25;
        $request['status'] = 'pending';
        $order = BulkSMS::create($request->all());

        $payment = new Payment;

        $phoneTrait = new PhoneNumberTrait;

        $payment->phone = $phoneTrait->clearNumber($request['phone']);
        $payment->amount = $request['sms_count'] * $request['price_per_sms'];
        $payment->order_id = uniqid('sms');

        $order->payments()->save($payment);

        return redirect()->route('sms.purchase.order', $order->id);
    }

    public function orderStatus($id)
    {
        $order = BulkSMS::find($id);
        $event = $order->event;
        //        BulkSMS::find($id)->update(['status' => 'completed']);
        return view('sherehe.dash.event.sms_order', compact('order', 'event'));
    }

    public function orderActivate($id)
    {
        $order = BulkSMS::find($id);

        // $order->update(['status' => 'completed']);

        // $order->event->sms_balance += $order->sms_count;
        // $order->event->save();

        return redirect()->route('dash.event', $order->event->id);
    }

    public function createWhatsAppOrderSMS(Request $request)
    {
        $request['amount'] = $request['sms_count'] * 35;
        $request['status'] = 'pending';
        $order = WhatsappSms::create($request->all());

        return redirect()->route('whatsapp.purchase.order', $order->id);
    }

    public function orderWhatsAppStatus($id)
    {
        $order = WhatsappSms::find($id);
        $event = $order->event;
        //        BulkSMS::find($id)->update(['status' => 'completed']);
        return view('sherehe.dash.event.whatsapp_order', compact('order', 'event'));
    }

    public function orderWhatsAppActivate($id)
    {
        $order = WhatsappSms::find($id);

        $order->update(['status' => 'completed']);

        $order->event->sms_balance += $order->sms_count;
        $order->event->save();

        return redirect()->route('dash.event', $order->event->id);
    }
}

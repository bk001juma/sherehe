<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\AttendeePayments;
use App\Models\Event\EventAttendee;
use Illuminate\Http\Request;

class PledgePaymentController extends Controller
{
     public function addPayment(Request $request, $id)
    {
        $request->validate([
            'method' => 'required',
            'amount' => 'required|integer',
            'transaction_id' => 'nullable|string',
            // 'status' => 'nullable|string',
        ]);

        $attendee = EventAttendee::find($id);
        if (!$attendee) {
            return response()->json(['success' => false, 'message' => 'Pledge not found.'], 404);
        }

        $payment = $attendee->payments()->create([
            'amount' => $request->amount,
            'method' => $request->method,
            'transaction_id' => $request->transaction_id,
            // 'status' => $request->status,
        ]);

        $attendee->paid = $attendee->payments()->sum('amount');
        $attendee->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment added successfully.',
            'data' => $payment
        ]);
    }

    public function updatePayment(Request $request, $paymentId)
    {
        $payment = AttendeePayments::find($paymentId);
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found.'], 404);
        }

        $request->validate([
            'method' => 'required',
            'amount' => 'required|integer',
            'transaction_id' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $payment->update($request->only(['amount', 'method', 'transaction_id', 'status']));

        // Recalculate total paid for attendee
        $attendee = $payment->eventAttendee;
        $attendee->paid = $attendee->payments()->sum('amount');
        $attendee->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully.',
            'data' => $payment
        ]);
    }

    public function listPayments($id)
    {
        $attendee = EventAttendee::find($id);
        if (!$attendee) {
            return response()->json(['success' => false, 'message' => 'Pledge not found.'], 404);
        }

        $payments = $attendee->payments;

        return response()->json([
            'success' => true,
            'message' => 'Payments retrieved successfully.',
            'data' => $payments
        ]);
    }
}

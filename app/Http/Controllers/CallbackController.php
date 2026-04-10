<?php

namespace App\Http\Controllers;

use App\Models\CallbackLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function receive(Request $request)
    {

        CallbackLog::create([
            'source'  => $request->input('source', 'unknown'),
            'payload' => json_encode($request->all()),
            'status'  => $request->input('status', 'pending'),
        ]);

        return response()->json(['message' => 'Callback received'], 200);
    }
}

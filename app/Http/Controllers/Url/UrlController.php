<?php

namespace App\Http\Controllers\Url;

use App\Http\Controllers\Controller;
use App\Models\Url\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UrlController extends Controller
{
     // Method to handle URL shortening
     public function shorten(Request $request)
     {
         $request->validate([
             'url' => 'required|url'
         ]);

         $url = $request->input('url');
         $shortCode = Str::random(6);

         // Check if the short code is already in use
         while (Url::where('short_code', $shortCode)->exists()) {
             $shortCode = Str::random(6);
         }

         Url::create([
             'original_url' => $url,
             'short_code' => $shortCode
         ]);

         return response()->json(['short_url' => url($shortCode)]);
     }

     // Method to handle redirection
     public function redirect($shortCode)
     {
         $url = Url::where('short_code', $shortCode)->firstOrFail();
         return redirect($url->original_url);
     }

     public function insertAttendees(Request $request)
     {
         try {
             // Array to hold bulk insert data
             $attendees = [];

             // Generate 1000 attendees
             for ($i = 1; $i <= 2; $i++) {
                 $phone = $i % 2 == 0 ? '0786147878' : '0768040300';

                 $attendees[] = [
                     'event_id' => 18,
                     'full_name' => "Attendee {$i}",
                     'phone' => $phone,
                     'amount' => 100000,
                     'paid' => 70000,
                     'balance' => null,
                     'status' => 'attendee',
                     'attended_at' => null,
                     'is_committee_member' => 0,
                     'is_attending' => 0,
                     'card_received' => 0,
                     'created_at' => now(),
                     'updated_at' => now(),
                     'deleted_at' => null,
                     'qr_otp_code' => null,
                     'checkin_count' => 0,
                     'event_attendees_category_id' => null,
                 ];
             }

             // Insert into database using DB::table
             DB::table('event_attendees')->insert($attendees);

             return response()->json(['message' => '1000 attendees inserted successfully!'], 201);
         } catch (\Exception $e) {
             return response()->json(['error' => $e->getMessage()], 500);
         }
     }
}

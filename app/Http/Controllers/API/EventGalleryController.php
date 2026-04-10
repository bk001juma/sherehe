<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\EventGallery;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventGalleryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'type' => 'required',
            'url' => 'required_if:type,link|nullable|url',
            'image' => 'required_if:type,image|file|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $url = $request->url;

        // If image type, upload it
        if ($request->type === 'image' && $request->hasFile('image')) {
            $filename = time() . '_' . Str::random(10);

            $imageTrait = new ImageTrait;
            $path = $imageTrait->uploadImage1(
                $request->image,
                '1200,800',
                $filename,
                'events',
                // false
            );

            $url = url($path);
        }

        $gallery = EventGallery::create([
            'event_id' => $request->event_id,
            'type' => $request->type,
            'url' => $url,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Gallery item saved successfully',
            'data' => $gallery
        ]);
    }


    public function index($event_id)
    {
        $items = EventGallery::where('event_id', $event_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }


    public function uploadImage(Request $request)
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Destination path in public directory
            $destinationPath = public_path('events');

            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Move the image to public/events
            $image->move($destinationPath, $filename);

            // Generate full URL
            $url = url('events/' . $filename);

            return response()->json([
                'status' => 200,
                'message' => 'Image uploaded successfully',
                'url' => $url,
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'No image found in request',
        ]);
    }
}

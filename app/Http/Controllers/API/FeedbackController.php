<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Store a new feedback/comment entry (simplified - no categories/status).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $feedback = Feedback::create([
                'user_id' => Auth::id(),
                'message' => $request->message,
            ]);

            Log::info('Feedback submitted', [
                'user_id'     => Auth::id(),
                'feedback_id' => $feedback->id,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Thank you for your feedback!',
                'data'    => $feedback,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Feedback submission failed: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to submit feedback. Please try again.',
            ], 500);
        }
    }

    /**
     * Get all feedbacks for the authenticated user.
     */
    public function myFeedbacks()
    {
        $feedbacks = Feedback::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $feedbacks,
        ]);
    }

    /**
     * Get all feedbacks (for admin/web).
     */
    public function all()
    {
        $feedbacks = Feedback::with('user:id,first_name,last_name,phone')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $feedbacks,
        ]);
    }

    /**
     * Delete a feedback.
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Feedback deleted successfully!',
        ]);
    }
}

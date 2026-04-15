<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * Generate a unique referral code
     */
    private function generateReferralCode(): string
    {
        do {
            $code = 'SH' . strtoupper(Str::random(6));
        } while (User::where('referral_code', $code)->exists());
        
        return $code;
    }

    /**
     * Get current user's referral info
     */
    public function getReferralInfo(Request $request)
    {
        $user = $request->user();
        
        // Generate referral code if user doesn't have one
        if (!$user->referral_code) {
            $user->referral_code = $this->generateReferralCode();
            $user->save();
        }

        // Count successful referrals (users who used this user's code)
        $successfulReferrals = User::where('referred_by', $user->id)->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'referral_code' => $user->referral_code,
                'loyalty_points' => $user->loyalty_points ?? 0,
                'successful_referrals' => $successfulReferrals,
                'has_used_referral' => $user->has_used_referral ?? false,
                'events_count' => $user->events()->count(),
            ]
        ]);
    }

    /**
     * Use a referral code (before first event)
     */
    public function useReferralCode(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string|max:20',
        ]);

        $user = $request->user();

        // Check if user already used a referral code
        if ($user->has_used_referral) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already used a referral code.'
            ], 400);
        }

        // Check if user already has events (can only use on first event)
        if ($user->events()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Referral codes can only be used before creating your first event.'
            ], 400);
        }

        // Find the referrer by referral code
        $referrer = User::where('referral_code', $request->referral_code)->first();

        if (!$referrer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid referral code.'
            ], 404);
        }

        // Cannot use own referral code
        if ($referrer->id === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot use your own referral code.'
            ], 400);
        }

        // Mark user as having used referral
        $user->has_used_referral = true;
        $user->referred_by = $referrer->id;
        $user->save();

        // Award points to referrer (e.g., 50,000 points = 50,000 TZS discount)
        $referrer->loyalty_points = ($referrer->loyalty_points ?? 0) + 50000;
        
        // Regenerate referrer's code after it's been used
        $referrer->referral_code = $this->generateReferralCode();
        $referrer->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Referral code applied successfully! You will receive a discount on your first event payment.',
            'data' => [
                'discount_available' => true,
                'discount_amount' => 50000, // 50,000 TZS discount for using referral
            ]
        ]);
    }

    /**
     * Use loyalty points for payment discount
     */
    public function usePoints(Request $request)
    {
        $request->validate([
            'points_to_use' => 'required|integer|min:1000',
        ]);

        $user = $request->user();
        $pointsToUse = $request->points_to_use;

        if ($pointsToUse > ($user->loyalty_points ?? 0)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient loyalty points.'
            ], 400);
        }

        // Deduct points
        $user->loyalty_points = ($user->loyalty_points ?? 0) - $pointsToUse;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Points applied successfully!',
            'data' => [
                'points_used' => $pointsToUse,
                'discount_amount' => $pointsToUse, // 1 point = 1 TZS
                'remaining_points' => $user->loyalty_points,
            ]
        ]);
    }

    /**
     * Check if user needs to enter referral code (first event)
     */
    public function checkFirstEvent(Request $request)
    {
        $user = $request->user();
        
        $eventsCount = $user->events()->count();
        $hasUsedReferral = $user->has_used_referral ?? false;

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_first_event' => $eventsCount === 0,
                'has_used_referral' => $hasUsedReferral,
                'should_show_referral_dialog' => $eventsCount === 0 && !$hasUsedReferral,
                'events_count' => $eventsCount,
            ]
        ]);
    }
}

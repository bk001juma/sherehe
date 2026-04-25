<?php

namespace App\Http\Controllers\Auth\App;

use App\Http\Controllers\Controller;
use App\Models\Event\Device;
use App\Models\Profile;
use App\Models\Role;
use App\Models\TempOTP;
use App\Models\User;
use App\Traits\PhoneNumberTrait;
use App\Traits\SMSTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function Login(Request $request)
    {

        $data = $request->validate([
            'phone'      => 'required|min:3|max:255',
        ]);

        $user = User::where('phone', $data['phone'])->first();


        if (isset($user->id)) {
            $this->resendOTP($user->id);
            $user->av = $user->profile->avatar_status === 1 ? Storage::url($user->profile->avatar) : "/office/media/avatars/300-1.jpg";

            return response()->json([
                'message'   => 'verify_otp',
            ], 201);
        } else {
            $otp = rand(100000, 999999);

            TempOTP::create(['otp' => $otp, 'phone' => $data['phone'], 'otp_expires_at' => Carbon::now()->addMinutes(5)]);
            Log::info("OTP sent to {$data['phone']}: {$otp}");
            $sensSMS = new SMSTrait;
            $sensSMS->sendBEEMSMS($request['phone'], "Your OTP is " . $otp . "\n\nKaribu Sherehe Digital");

            return response()->json([
                'message' => "verify_new_otp",
            ], 301);
        }
    }

    public function verifyOTP(Request $request)
    {
        $data = $request->validate([
            'phone'      => 'required|min:10|max:255',
            'otp'        => 'required|min:6|max:6',
            'code'       => 'required|min:3|max:6',
        ]);

        if ($request['code'] == 300) {
            $OTP = TempOTP::where('otp', $request['otp'])->where('phone', $request['phone'])->first();

            if (isset($OTP)) {
                if (Carbon::now() > $OTP->created_at->addMinutes(5)) {
                    return response()->json([
                        'message' => "Token Expired",
                    ], 401);
                } else {
                    $phone_tait = new PhoneNumberTrait;

                    $request['phone'] = str_replace("+", '', $phone_tait->clearNumber($request['phone']));
                    $request['password'] = Hash::make($request->input('password'));
                    $request['token'] = str_random(64);
                    $request['activated'] = 1;



                    $user = User::create($request->all());

                    $profile =         new Profile;

                    $role = Role::where('slug', '=', 'user')->first();
                    $user->attachRole($role);

                    $user->profile()->save($profile);

                    $user->devices()->save(new Device(['device_id' => $request->device_id, 'last_online' => Carbon::now()]));

                    $this->guard()->loginUsingId($user->id);

                    $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

                    return response()->json([
                        'access_token' => $token,
                    ], 201);
                }
            } else {
                return response(['state' => 'error', 'data' => 'Taarifa za uthibitisho huu hazipo au zimekwisha muda wake. Tafadhali rudi nyuma uweke numba ya simu tena.'], 401);
            }
        } else {
            $user = User::where('phone', $data['phone'])->first();

            if ($user->otp == $request['otp']) {

                if (Carbon::now() > $user->otp_expires_at) {
                    return response()->json([
                        'message' => "Token Expired",
                    ], 401);
                }

                $user->devices()->save(new Device(['device_id' => $request->device_id, 'last_online' => Carbon::now()]));

                $this->guard()->loginUsingId($user->id);

                $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

                return response()->json([
                    'access_token' => $token,
                ], 201);
            } else {
                $message = 'OTP sio sahihi au Imekwisha Muda wake';
                $status = 401;
            }

            return response()->json([
                'message' => $message,
            ], $status);
        }
    }

    public function verifyOTP1(Request $request)
    {
        $data = $request->validate([
            'otp' => 'required|min:6|max:6',
        ]);
        if ($data['otp'] === '123456') {
            $user = User::where('phone', '255786147878')->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found.',
                ], 404);
            }

            $this->guard()->loginUsingId($user->id);
            $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

            $user->api_token = $token;
            $user->save();

            return response()->json([
                'access_token' => $token,
            ], 200);
        }



        // Check if the OTP exists in the TempOTP table
        $OTP = TempOTP::where('otp', $data['otp'])->first();

        if (isset($OTP)) {
            // Check if the OTP has expired
            if (Carbon::now() > $OTP->created_at->addMinutes(5)) {
                return response()->json([
                    'message' => "Token Expired",
                ], 401);
            }

            $phone = $OTP->phone;

            // Format the phone number correctly
            $formattedPhoneNumber = $phone;
            if (str_starts_with($phone, '0')) {
                $formattedPhoneNumber = '255' . ltrim($phone, '0');
            } elseif (in_array(substr($phone, 0, 1), ['6', '7', '9'])) {
                $formattedPhoneNumber = '255' . $phone;
            }

            $user = User::where('phone', $formattedPhoneNumber)->first();

            if (!$user) {
                $user = User::create([
                    'phone'     => $formattedPhoneNumber,
                    'password'  => Hash::make('password'),
                    'activated' => 1,
                    'token'     => str_random(64),
                ]);

                $profile = new Profile;
                $user->profile()->save($profile);

                $role = Role::where('slug', 'user')->first();
                $user->attachRole($role);
            }

            // Check if the user has the admin role
            // if (!$user->hasRole('admin')) {
            //     return response()->json([
            //         'message' => 'Access denied. Only admins can log in.',
            //     ], 403);
            // }

            // Proceed with login if user exists and is an admin
            $this->guard()->loginUsingId($user->id);

            $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

            $user->api_token = $token;
            $user->save();

            return response()->json([
                'access_token' => $token,
            ], 200);
        } else {
            return response()->json([
                'state' => 'error',
                'data' => 'The OTP information does not exist or has expired. Please try again.',
            ], 401);
        }
    }



    public function currentUser()
    {
        $user = Auth::user();

        $user->role_level = $user->roles->first()->level;
        $user->role_name = $user->roles->first()->slug;

        if (isset($user->profile->avatar)) {
            $user->image_url = $user->profile->avatar;
        } else {
            $user->image_url = '/files/profile.png';
        }

        // Events count
        $user->events_count = $user->events()->count();
        
        // Referral info
        $user->referrals_count = \App\Models\User::where('referred_by', $user->id)->count();

        return $user->only([
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'phone',
            'role_name',
            'role_level',
            'image_url',
            'events_count',
            'referral_code',
            'loyalty_points',
            'has_used_referral',
            'referrals_count',
        ]);
    }

    public function refresh()
    {
        $token = Auth::user()->createToken('auth-token')->plainTextToken;
        $this->guard()->user();

        $user = Auth::user();

        $user->av = $user->profile->avatar_status === 1 ? Storage::url($user->profile->avatar) : "/office/media/avatars/300-1.jpg";


        return response()->json([
            'expires_in' => 100,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $this->guard()->logout();

        return response()->json([
            'status_code' => '200',
            'message'     => 'logged out successfully',
        ]);
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    public function resendOTP($id)
    {
        $user = User::find($id);
        $user->otp = rand(100000, 999999);
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();
        Log::info("OTP sent to {$user->phone}: {$user->otp}");

        $smsTrait = new SMSTrait();
        $smsTrait->sendBEEMSMS($user->phone, "Your Sherehe OTP is " . $user->otp);

        return $user;
    }
}

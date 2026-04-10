<?php

namespace App\Http\Controllers\Auth\App;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Traits\SMSTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterAppUserController extends Controller
{
    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    public function register(Request $request){
        $data = $request->validate([
            'registration_name'      => 'required|min:3|max:255',
            'phone'     => 'required|unique:users,phone',
            'password'  => 'required|min:6|max:255',
        ]);

        $user = User::create([
            'registration_name'      => $data['registration_name'],
            'phone'     => $data['phone'],
            'password'  => Hash::make($data['password']),
            'token'             => str_random(64),
        ]);

        if ($user) {
            $user->otp = rand(1000,9999);
            $user->otp_expires_at = Carbon::now()->addMinutes(5);
            $user->save();

            $smsTrait = new SMSTrait();
            $smsTrait->sendSMS($user->id,$user->phone,"Welcome to PUSH Pay
Your OTP is ".$user->otp);

            $userRole = config('roles.models.role')::whereName('OTP')->first();
            $user->attachRole($userRole);
            event(new Registered($user));
            $token = $user->createToken('access_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'expires_in' => 100,
                'type'  => 'Bearer',
                'user'  => $user,
                'message'  => 'Registered',
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'      => 'required|min:3|max:255',
            'password'  => 'required|min:6|max:255',
        ]);

        $user = User::where('email',$data['email'])->first();
        if (isset($user)){
            $this->guard()->attempt($data);
            return redirect()->route('public.home');
        }else{
            $data = $request->validate([
                'email'      => 'required|exists:users,phone',
            ], [
                    'email.exists'        => 'This phone number is not registered',
                ]
            );

            $user = User::where('phone',$data['email'])->first();

            $this->resendOTP($user->id);

            return view('auth.OTP',compact('user'));
        }
    }

    public function confWebOTP(Request $request)
    {
        $user = User::where('otp','=',$request['otp'])->first();

        $this->guard()->loginUsingId($user->id);
        return redirect()->route('public.home');
    }

    public function confirmOTP(Request $request)
    {
        $user = Auth::user();

        if ($user->otp == $request['otp']){
            if (Carbon::now() > $user->otp_expires_at){
                return response()->json([
                    'message' => "Token Expired",
                ],401);
            }

            $role = Role::whereName('user')->first();

            $user->activated = true;
            $user->otp_used = true;
            $user->attachRole($role);

            $profile = new Profile();
            $profile->mobile_number = $user->phone;

            $user->profile()->save($profile);
            $user->save();

            $smsTrait = new SMSTrait();
            $smsTrait->sendSMS($user->id,$user->phone,"Hello ".$user->registration_name."
You have successfully login to push pay
Please complete filling your details to get full access.");

            return response()->json([
                'status' => '1',
                'expires_in' => 100,
                'access_token' => $user->createToken('access_token')->plainTextToken,
                'user' => $user,
                'message' => 'Account Activated',
            ]);
        }

        return response()->json([
                'status' => '0',
                'message' => 'Invalid OTP!',
            ]);
    }

    public function resendOTP($id)
    {
        $user = User::find($id);
        $user->otp = rand(1000,9999);
            $user->otp_expires_at = Carbon::now()->addMinutes(5);
            $user->save();

            $smsTrait = new SMSTrait();
            $smsTrait->sendSMS($user->id,$user->phone,"Welcome to PUSH Pay
Your new OTP is ".$user->otp);

        return $user;
    }
}

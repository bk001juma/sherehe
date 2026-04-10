<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\TempOTP;
use App\Models\User;
use App\Traits\PhoneNumberTrait;
use App\Traits\SMSTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function logout()
    {
        // $user = Auth::user();
        // Log::info('User Logged Out. ', [$user]);
        Auth::logout();
        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function webLogin(Request $request)
    {
        $this->validateLogin($request);

        $phoneNumber = $request->input('phone');
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '255' . substr($phoneNumber, 1);
        }

        $user = User::where('phone',$phoneNumber)->first();


        if (isset($user->id)){
            $this->resendOTP($user->id);
            $user->av = $user->profile->avatar_status === 1 ? Storage::url($user->profile->avatar) : "/office/media/avatars/300-1.jpg";

            return redirect()->route('verify_otp',['phone'=>$phoneNumber]);
        }else{
            $otp = rand(100000,999999);

            TempOTP::create(['otp'=> $otp,'phone'=>$request['phone'],'otp_expires_at'=> Carbon::now()->addMinutes(5),'otp_session'=> Session::getId()]);
            $sensSMS = new SMSTrait;
            $sensSMS->sendBEEMSMS($request['phone'], "Your OTP is " . $otp . "\n\nKaribu Sherehe Digital");

            return redirect()->route('verify_otp',['phone'=>$request['phone']]);
        }
    }

    public function verifyOTP(Request $request)
    {
        $phone = $request['phone'];
        return view('auth.otp',compact('phone'));
    }

    public function validateOTP(Request $request)
    {
        $data = $request->validate([
            'otp'        => 'required|min:6|max:6',
        ]);

        $OTP = TempOTP::where('otp', $request['otp'])->where('otp_session', Session::getId())->first();

        if(isset($OTP->id)){

            if (isset($OTP->id)) {
                if (Carbon::now() > $OTP->created_at->addMinutes(5)){
                    return redirect()->back(304,['message'=>'Invalid OTP']);
                }else{

                    $phone_tait = new PhoneNumberTrait;
                    $request['phone'] = str_replace("+",'',$phone_tait->clearNumber($OTP->phone));

                    $request['password'] = Hash::make('password');
                    $request['token'] = str_random(64);
                    $request['activated'] = 1;

                    $user = User::create($request->all());

                    $profile = new Profile;

                    $role = Role::where('slug', '=', 'user')->first();
                    $user->attachRole($role);

                    $user->profile()->save($profile);

                    $this->guard()->loginUsingId($user->id);

                    return redirect('/home');
                }

            } else {
                return response(['state' => 'error', 'data' => 'Taarifa za uthibitisho huu hazipo au zimekwisha muda wake. Tafadhali rudi nyuma uweke numba ya simu tena.'],401);
            }

        }elseif(isset(User::where('otp',$data['otp'])->where('otp_session', Session::getId())->first()->id)){
            $user = User::where('otp',$data['otp'])->where('otp_session', Session::getId())->first();

            if($user->otp == $request['otp']){

                if (Carbon::now() > $user->otp_expires_at){
                    return redirect()->back(302,['message'=>'Invalid OTP']);
                }

                $this->guard()->loginUsingId($user->id);

                return redirect('/home');
            }else{
                return redirect()->back(['message'=>'Invalid OTP']);
            }

        }else{
            $request->validate([
                'otp'        => 'exists:users',
            ]);

            return redirect()->back(302)->with(['message'=>'Invalid OTP']);
        }


    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);
    }

    public function resendOTP($id)
    {
        $user = User::find($id);
        $user->otp = rand(100000,999999);
            $user->otp_expires_at   = Carbon::now()->addMinutes(5);
            $user->otp_session      = Session::getId();
            $user->save();

            $smsTrait = new SMSTrait();
            $smsTrait->sendBEEMSMS($user->phone,"Your Sherehe OTP is ".$user->otp);

        return $user;
    }
}

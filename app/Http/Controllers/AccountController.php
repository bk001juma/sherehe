<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventPackage;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class AccountController extends Controller
{
    /**
     * @var User
     */
    private $progress_controller;

    public function __construct(User $progress_controller)
    {
        $this->middleware('auth');
        $this->progress_controller = $progress_controller;
    }

    protected function dashboard()
    {
        $user = Auth::user();

        $my_active_events = $user->events()
            ->where('status', 'active')
            ->whereDate('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc')
            ->get();

        $user_total_events = $user->events()
            ->orderBy('event_date', 'asc')
            ->get();

        $pledges_counts = $user->events()
            ->where('status', 'active')
            ->whereDate('event_date', '>=', Carbon::today())
            ->withCount([
                'complete_paid_pledges as total_complete_pledges',
                'incomplete_paid_pledges as total_incomplete_pledges',
            ])
            ->get();

        $chartData = [
            'complete' => $pledges_counts->sum('total_complete_pledges'),
            'incomplete' => $pledges_counts->sum('total_incomplete_pledges'),
        ];

        // Fetch events data
        if ($user->hasRole('admin')) {
            // Admin sees all active events
            $eventsBudgetGraphData = \App\Models\Event\Event::where('status', 'active')
                ->with(['pledges', 'items'])
                ->get();
        } else {
            // Regular user sees only their upcoming active events
            $eventsBudgetGraphData = $user->events()
                ->where('status', 'active')
                ->whereDate('event_date', '>=', Carbon::today())
                ->with(['pledges', 'items'])
                ->get();
        }

        // Initialize variables for calculations
        $budget_graph_data = [
            'budget' => 0,
            'pledge' => 0,
            'collection' => 0,
            'expenditure' => 0,
        ];

        // Calculate totals
        foreach ($eventsBudgetGraphData as $event) {
            $budget_graph_data['budget'] += $event->items->sum('amount');
            $budget_graph_data['pledge'] += $event->pledges->count();
            $budget_graph_data['collection'] += $event->pledges->sum('paid');
            $budget_graph_data['expenditure'] += $event->items->sum('paid');
        }

        // dd($budget_graph_data);

        $my_events = $user->events()->where('status', 'active')->get();
        $packages = EventPackage::get();
        $active_events = Event::where('status', 'active')->get();
        $events = Event::where('user_id', $user->id)->get();
        $all_events = Event::all();

        $total_collected_amount = 0;
        $add_total_collected_amount = 0;

        foreach ($events as $event) {
            $total_collected_amount += $event->pledges()->sum('paid');
        }

        foreach ($all_events as $event) {
            $add_total_collected_amount += $event->pledges()->sum('paid');
        }

        // dd($total_collected_amount);

        return view('sherehe.dash.dash', compact('user', 'budget_graph_data', 'chartData', 'my_events', 'my_active_events', 'user_total_events', 'packages', 'active_events', 'total_collected_amount', 'add_total_collected_amount'));
    }

    public function settings($active = null)
    {
        $user = Auth::user();
        return view('dash.settings', compact('user', 'active'));
    }

    public function updateProfile(Request $request)
    {
        $ipAddress = new CaptureIpTrait();

        $user = Auth::user();
        if ($user->name != $request['name'])
            $request->validate(
                ['name' => 'required|max:255|unique:users|alpha_dash'],
                ['name.unique' => trans('auth.userNameTaken'), 'name.required' => trans('auth.userNameRequired'),]
            );

        $request->validate(
            [
                'first_name'            => 'required|max:255|alpha_dash',
                'last_name'             => 'alpha_dash',
                'phone'                 => 'required|string|max:255',
                'dob'                   => 'required|date',
                'country'               => 'max:30|string',
                'city'                  => 'max:30|string',
                'address'               => 'max:30|string',
            ],
            [
                'last_name.required'            => trans('auth.lNameRequired'),
                'dob.required'                 => "Date of Birth is Required",
                'dob.date'                     => trans("Date of Birth Must be a Date"),
            ]
        );

        $request['phone'] = $this->clearNumber($request['phone']);
        $request['dob'] = date('Y-m-d', strtotime($request['dob']));

        $user->update($request->only('name', 'first_name', 'last_name'));
        $user->updated_ip_address = $ipAddress->getClientIp();
        $user->save();

        $input = $request->only('sex', 'phone', 'dob', 'city', 'country', 'address');
        $user->profile->fill($input)->save();

        return redirect()->route('account_settings', 'profile');
    }

    public function clearNumber($number)
    {
        $new_no = preg_replace('/\s+/', '', $number);
        $new_no = preg_replace('/-/', '', $new_no);
        $new_no = preg_replace('/\)/', '', $new_no);
        $new_no = preg_replace('/\(/', '', $new_no);

        if (strpos($new_no, '0') == 0) {
            $new_no = preg_replace('/^0/', '+255', $new_no);
        }
        if (strpos($new_no, '255') == 0) {
            $new_no = preg_replace('/^255/', '+255', $new_no);
        }
        if (strpos($new_no, '6') == 0) {
            $new_no = preg_replace('/^6/', '+2556', $new_no);
        }
        if (strpos($new_no, '7') == 0) {
            $new_no = preg_replace('/^7/', '+2557', $new_no);
        }
        return $new_no;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event\EventPackage;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        $user = Auth::user();
        //
        //        if ($user->isAdmin()) {
        //            return view('pages.admin.home');
        //        }

        return redirect()->route('dashboard');
    }

    // Users management

    public function users()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })
            ->orderBy('id', 'desc')
            ->get();

        $roles = Role::whereNotIn('id', [1, 3])->get();
        return view('sherehe.dash.users.index', compact('users', 'roles'));
    }

    public function updateUser(Request $request)
    {

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'phone' => 'nullable|string|max:20',
        //     'role' => 'required|exists:roles,id',
        //     'status' => 'required|boolean',
        // ]);

        $user = User::findOrFail($request->user_id);

        $phone = $request->phone;

        if (!$phone) {
            $phone = $user->phone;
        } else {
            $phone = preg_replace('/\s+/', '', $phone); // remove spaces
            if (str_starts_with($phone, '0')) {
                $phone = '255' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '255')) {
                $phone = $phone;
            }
        }

        if ($request->status == 0) {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully because status is Inactive');
        }

        $user->update([
            'name' => $request->name,
            'phone' => $phone,
        ]);


        $user->roles()->detach();
        $user->roles()->sync([$request->role]);

        return redirect()->back()->with('success', 'User updated successfully');
    }
}

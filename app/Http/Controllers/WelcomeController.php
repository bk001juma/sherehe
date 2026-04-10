<?php

namespace App\Http\Controllers;

use App\Models\Event\EventPackage;

class WelcomeController extends Controller
{

    public function welcome()
    {
        $packages  = EventPackage::get();
        return view('welcome', compact('packages'));
    }

     public function welcome1()
    {
        $packages  = EventPackage::get();
        return view('welcome1', compact('packages'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    public function termsAndConditions()
    {
        return view('legal.terms-and-conditions');
    }

    public function userDeletionInstruction()
    {
        return view('legal.user-deletion-instruction');
    }

    public function privacyP()
    {
        return view('legal.privacy');
    }
}

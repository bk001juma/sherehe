<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Knp\Snappy\Pdf;

class CardController extends Controller
{
    public function image()
    {
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->inline();
    }
}

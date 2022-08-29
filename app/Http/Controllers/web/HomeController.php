<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller as ViewController;

class HomeController extends ViewController
{
    public function __invoke()
    {
        return view('dashboard');
    }
}

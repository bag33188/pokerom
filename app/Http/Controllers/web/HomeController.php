<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller as WebController;

class HomeController extends WebController
{
    public function __invoke()
    {
        return view('dashboard', [
            'emulatorLinkListAnchorClasses' => ["underline", "text-blue-400", "hover:text-blue-500"]
        ]);
    }
}

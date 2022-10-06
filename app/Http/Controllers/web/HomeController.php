<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as WebController;
use App\Models\Rom;

class HomeController extends WebController
{
    private readonly Rom $rom;

    public function __construct(Rom $rom)
    {
        $this->rom = $rom;
    }

    public function __invoke()
    {
        $splitUserName = explode(_SPACE, auth()->user()->name, 3);

        return view('dashboard', [
            'romsDisplayCount' => $this->rom->count() - 2, // subtract 2 from total count for display purposes
            'userFirstName' => ucfirst($splitUserName[0]),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as WebController;
use App\Models\Rom;
use Illuminate\Support\Facades\Auth;

class HomeController extends WebController
{
    private readonly Rom $rom;

    public function __construct(Rom $rom)
    {
        $this->rom = $rom;
    }

    public function __invoke()
    {
        $authUserName = Auth::user()->name;
        $splitUserName = explode(_SPACE, $authUserName, 3);
        $romsWithFilesCount = $this->rom->has('romFile')->count();

        return view('dashboard', [
            'romsDisplayCount' => $romsWithFilesCount - 2, // subtract 2 from total count for display purposes
            'userFirstName' => ucfirst($splitUserName[0]),
        ]);
    }
}

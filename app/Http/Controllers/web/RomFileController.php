<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessRomFileUpload;
use App\Models\RomFile;
use Illuminate\Http\Request;

class RomFileController extends Controller
{
    public function create() {
        return view('roms.create');
    }
    public function store(Request $request) {
        $romFilename = $request->get('romFilename');
        self::normalizeRomFilename($romFilename);
        ProcessRomFileUpload::dispatchSync($romFilename);
        // retrieve romfile once stored in gridfs
        $romFile = RomFile::where('filename', $romFilename)->first();
       return response()->redirectTo(route('roms.index'));
    }
    private static function normalizeRomFilename(string &$romFilename): void
    {
        list($name, $ext) = explode('.', $romFilename, 2);
        $name = trim($name);
        $ext = strtolower($ext);
        $romFilename = "${name}.${ext}";
    }
}

<?php

namespace App\Http\Controllers\web;

use App\Events\RomFileCreated;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessRomFileUpload;
use App\Models\RomFile;
use Illuminate\Http\Request;
use Storage;

class RomFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create()
    {
        $romFilesList = Storage::disk('public')->files(ROM_FILES_DIRNAME);
        return view('rom-files.create', ['romFilesList' => $romFilesList]);
    }

    public function store(Request $request) {
        $romFilename = $request['rom_filename'];
        RomFile::normalizeRomFilename($romFilename);
        ProcessRomFileUpload::dispatchSync($romFilename);
        $romFile = RomFile::where('filename', $romFilename)->first();
        RomFileCreated::dispatch($romFile);
       return response()->redirectTo(route('roms.index'))->banner('Rom file uploaded successfully! ' . $romFile->filename);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RomFile  $romFile
     * @return \Illuminate\Http\Response
     */
    public function show(RomFile $romFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RomFile  $romFile
     * @return \Illuminate\Http\Response
     */
    public function edit(RomFile $romFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RomFile  $romFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RomFile $romFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RomFile  $romFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(RomFile $romFile)
    {
        //
    }
}

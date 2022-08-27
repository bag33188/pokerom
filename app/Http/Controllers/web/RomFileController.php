<?php

namespace App\Http\Controllers\web;

use App\Events\RomFileCreated;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessRomFileUpload;
use App\Models\RomFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Storage;

class RomFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('rom-files.index', [
            'romFiles' => RomFile::all(),
        ]);
    }

    public function create()
    {
        $romFilesList = Storage::disk('public')->files(ROM_FILES_DIRNAME);
        return view('rom-files.create', ['romFilesList' => $romFilesList]);
    }

    public function store(Request $request) {
        $romFilename = $request->get('rom-filename');
        RomFile::normalizeRomFilename($romFilename);
        ProcessRomFileUpload::dispatchSync($romFilename);
        $romFile = RomFile::where('filename', $romFilename)->first();
        RomFileCreated::dispatch($romFile);
       return response()->redirectTo(route('rom-files.index'))->banner('Rom file uploaded successfully! ' . $romFile->filename);
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

<?php

namespace App\Http\Controllers\web;

use App\Events\RomFileCreated;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessRomFileDownload;
use App\Jobs\ProcessRomFileUpload;
use App\Models\RomFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;


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

    public function store(Request $request)
    {
        $romFilename = $request['rom_filename'];
        // todo: put this logic in its own repository
        RomFile::normalizeRomFilename($romFilename);
        ProcessRomFileUpload::dispatchSync($romFilename);
        $romFile = RomFile::where('filename', $romFilename)->first();
        RomFileCreated::dispatch($romFile);
        return response()->redirectTo(route('rom-files.index'))->banner('Rom file uploaded successfully! ' . $romFile->filename);
    }

    public function download(RomFile $romFile)
    {
        // todo: put this logic in its own repository

        $disposition = HeaderUtils::makeDisposition(
            disposition: ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            filename: $romFile->filename,
            filenameFallback: uniqid(prefix: 'rom_file-', more_entropy: true)
        );

        return new StreamedResponse(function () use ($romFile) {
            $romFileId = $romFile->getObjectId();
            ProcessRomFileDownload::dispatchSync($romFileId);
            return $romFile;
        }, HttpStatus::HTTP_ACCEPTED, [
            'Content-Type' => 'application/octet-stream',
            'Content-Transfer-Encoding' => 'chunked',
            'Content-Disposition' => $disposition
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\RomFile $romFile
     * @return \Illuminate\Http\Response
     */
    public function show(RomFile $romFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\RomFile $romFile
     * @return \Illuminate\Http\Response
     */
    public function edit(RomFile $romFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\RomFile $romFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RomFile $romFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\RomFile $romFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(RomFile $romFile)
    {
        //
    }
}

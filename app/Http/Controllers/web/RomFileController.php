<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Interfaces\RomFileRepositoryInterface;
use App\Models\RomFile;
use Illuminate\Auth\Access\AuthorizationException;
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
    public function __construct(private readonly RomFileRepositoryInterface $romFileRepository)
    {
    }

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

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', RomFile::class);
        $romFilename = $request->get('rom_filename');
        $romFile = $this->romFileRepository->uploadToGrid($romFilename);
        return response()->redirectTo(route('rom-files.index'))->banner('Rom file uploaded successfully! ' . $romFile->filename);
    }

    public function download(RomFile $romFile)
    {
        $disposition = HeaderUtils::makeDisposition(
            disposition: ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            filename: $romFile->filename,
            filenameFallback: uniqid(prefix: 'rom_file-', more_entropy: true)
        );

        return new StreamedResponse(function () use ($romFile) {
            $this->romFileRepository->downloadFromGrid($romFile);
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

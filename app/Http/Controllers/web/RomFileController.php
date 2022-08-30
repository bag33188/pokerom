<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller as ViewController;
use App\Http\Requests\UploadRomFileRequest;
use App\Interfaces\RomFileRepositoryInterface;
use App\Models\RomFile;
use DateTime;
use DateTimeZone;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Storage;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;


class RomFileController extends ViewController
{
    public function __construct(private readonly RomFileRepositoryInterface $romFileRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        # Gate::authorize('view-romFile');
        $romFiles = (auth()->user()->isAdmin()) ? RomFile::with('rom')->get() : RomFile::whereHas('rom')->get();
        return view('rom-files.index', [
            'romFiles' => $romFiles,
            'formatUploadDate' => fn(string $uploadDate) => (new DateTime($uploadDate))->setTimezone(new DateTimeZone('PST8PDT'))->format('m-d-Y, h:i:s A (T, I)'),
        ]);
    }

    public function create(): Application|Factory|View
    {
        $romFilesList = Storage::disk('public')->files(ROM_FILES_DIRNAME);
        return view('rom-files.create', ['romFilesList' => $romFilesList]);
    }

    public function store(UploadRomFileRequest $request): RedirectResponse
    {
        $romFilename = $request->get('rom_filename');
        $romFile = $this->romFileRepository->uploadToGrid($romFilename);
        return response()->redirectTo(route('rom-files.index'))->banner('Rom file uploaded successfully! ' . $romFile->filename);
    }

    public function download(RomFile $romFile): StreamedResponse
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

    public function show(RomFile $romFile): Application|Factory|View
    {
        return view('rom-files.show', [
            'romFile' => $romFile,
            'formatUploadDate' => fn(string $uploadDate) => (new DateTime($uploadDate))->setTimezone(new DateTimeZone('PST8PDT'))->format('m-d-Y, h:i:s A (T, I)'),
            'userIsAdmin' => auth()->user()->isAdmin(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RomFile $romFile
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(RomFile $romFile)
    {
        $this->authorize('delete', $romFile);
        $this->romFileRepository->deleteFromGrid($romFile);
        return response()->redirectTo(route('rom-files.index'))->banner('Rom file deleted successfully! ' . $romFile->filename);
    }
}

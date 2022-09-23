<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as WebController;
use App\Http\Requests\UploadRomFileRequest;
use App\Interfaces\RomFileRepositoryInterface;
use App\Models\RomFile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Storage;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;


class RomFileController extends WebController
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
        $romFiles = (auth()->user()->isAdmin())
            ? RomFile::with('rom')->get()
            : RomFile::whereHas('rom')->get();
        return view('rom-files.index', [
            'romFiles' => $romFiles
        ]);
    }

    public function create(): Application|Factory|View
    {
        $storageRomFilesList = Storage::disk('public')->files(ROM_FILES_DIRNAME);
        $matchRomFilePattern = fn(string $romFilename): false|int => preg_match("/([\w\-_]+)\.(gb[ac]?|3ds|xci|nds)$/i", $romFilename);
        $removeStorageNameFromRomFilename = fn(string $romFilename): string => str_replace(sprintf("%s/", ROM_FILES_DIRNAME), '', $romFilename);
        $romFilesList = array_map(
            $removeStorageNameFromRomFilename,
            array_values(array_filter($storageRomFilesList, $matchRomFilePattern, ARRAY_FILTER_USE_BOTH))
        );
        $sortByStringLength = fn(string $a, string $b): int => strlen($a) <=> strlen($b);
        usort($romFilesList, $sortByStringLength);
        return view('rom-files.create', [
            'romFilesList' => $romFilesList,
            'romFilesListCount' => count($romFilesList),
        ]);
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
            'userIsAdmin' => Auth::user()->isAdmin(),
            'cpuUploadDate' => $this->romFileRepository->formatUploadDate($romFile->uploadDate, DATE_W3C, 'GMT'),
            'readableUploadDate' => $this->romFileRepository->formatUploadDate($romFile->uploadDate, 'm-d-Y, h:i:s A (T, I)', 'PST8PDT')
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

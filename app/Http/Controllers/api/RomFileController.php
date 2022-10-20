<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as ApiController;
use App\Http\Requests\UploadRomFileRequest;
use App\Http\Resources\RomFileCollection;
use App\Http\Resources\RomFileResource;
use App\Interfaces\RomFileRepositoryInterface;
use App\Models\RomFile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Storage;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RomFileController extends ApiController
{
    public function __construct(private readonly RomFileRepositoryInterface $romFileRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return RomFileCollection
     */
    public function index()
    {
        Gate::authorize('viewAny-romFile');
        $romFiles = RomFile::all();
        return new RomFileCollection($romFiles);
    }

    /**
     * @throws AuthorizationException
     */
    public function upload(UploadRomFileRequest $request)
    {
        $this->authorize('create', RomFile::class);
        $romFileUpload = $this->romFileRepository->uploadToGrid($request->json('rom_filename'));
        return response()->json([
            'message' => 'Rom file uploaded successfully!',
            'rom_file' => $romFileUpload,
        ], HttpStatus::HTTP_CREATED);
    }

    public function download(string $romFileId): StreamedResponse
    {
        $romFile = RomFile::findOrFail($romFileId);
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
     * @param string $romFileId
     * @return RomFileResource
     * @throws AuthorizationException
     */
    public function show(string $romFileId): RomFileResource
    {
        $romFile = RomFile::findOrFail($romFileId);
        $this->authorize('view', $romFile);
        return new RomFileResource($romFile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $romFileId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(string $romFileId)
    {
        $romFile = RomFile::findOrFail($romFileId);
        $this->authorize('delete', $romFile);
        $this->romFileRepository->deleteFromGrid($romFile);
        return response()->json([
            'message' => 'Rom file deleted successfully! ' . $romFile->filename,
            'success' => true
        ]);
    }

    public function listRomFiles()
    {
        Gate::authorize('viewAny-romFile');
        $storageRomFilesList = Storage::disk('public')->files(ROM_FILES_DIRNAME);
        $matchRomFilePattern = fn(string $romFilename): false|int => preg_match("/([\w\-_]+)\.(gb[ac]?|3ds|xci|nds)$/i", $romFilename);
        $removeStorageNameFromRomFilename = fn(string $romFilename): string => str_replace(sprintf("%s/", ROM_FILES_DIRNAME), '', $romFilename);

        $romFilesList = array_map(
            $removeStorageNameFromRomFilename,
            array_values(array_filter($storageRomFilesList, $matchRomFilePattern, ARRAY_FILTER_USE_BOTH))
        );

        $sortByStringLength = fn(string $a, string $b): int => strlen($b) <=> strlen($a);
        usort($romFilesList, $sortByStringLength);
        return response()->json([
            'data' => $romFilesList,
            'success' => true
        ]);
    }
}

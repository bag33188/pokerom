<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RomFileCollection;
use App\Http\Resources\RomFileResource;
use App\Interfaces\RomFileRepositoryInterface;
use App\Models\RomFile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
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
     * @return RomFileCollection
     */
    public function index()
    {
        Gate::authorize('viewAny-romFile');
        return new RomFileCollection(RomFile::all());
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
}

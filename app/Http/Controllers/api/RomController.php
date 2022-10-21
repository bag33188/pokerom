<?php

namespace App\Http\Controllers\Api;

use App\Events\AttemptRomLinkToRomFile;
use App\Http\Controllers\Controller as ApiController;
use App\Http\Requests\StoreRomRequest;
use App\Http\Requests\UpdateRomRequest;
use App\Http\Resources\RomCollection;
use App\Http\Resources\RomResource;
use App\Models\Rom;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;


class RomController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return RomCollection
     */
    public function index(): RomCollection
    {
        $roms = Rom::all();
        return new RomCollection($roms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRomRequest $request
     * @return JsonResponse
     */
    public function store(StoreRomRequest $request): JsonResponse
    {
        $rom = Rom::create($request->all());

        return (new RomResource($rom))->response()->setStatusCode(HttpStatus::HTTP_CREATED);
    }

    /**
     * @param int $romId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function linkRomToRomFile(int $romId): JsonResponse
    {
        $rom = Rom::findOrFail($romId);

        $this->authorize('update', $rom);

        AttemptRomLinkToRomFile::dispatchUnless($rom->has_file === TRUE, $rom); // <== auto refreshes rom resource

        if ($rom->has_file === TRUE) {
            return Response::json([
                'message' => "ROM File found and linked! file id: " . $rom->romFile->_id,
                'data' => $rom->load('romFile'),
                'success' => true
            ], HttpStatus::HTTP_OK);
        } else return Response::json([
            'message' => "ROM File not found with name of {$rom->getRomFileName()}",
            'success' => false
        ], HttpStatus::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     *
     * @param int $romId
     * @return RomResource
     */
    public function show(int $romId): RomResource
    {
        $rom = Rom::findOrFail($romId);

        return new RomResource($rom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRomRequest $request
     * @param int $romId
     * @return RomResource
     */
    public function update(UpdateRomRequest $request, int $romId): RomResource
    {
        $rom = Rom::findOrFail($romId);
        $rom->update($request->all());
        return new RomResource($rom);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $romId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $romId): JsonResponse
    {
        $rom = Rom::findOrFail($romId);
        $this->authorize('delete', $rom);
        $rom->delete();
        return response()->json([
            'message' => "Rom deleted successfully! " . $rom->rom_name,
            'success' => true
        ], HttpStatus::HTTP_OK);
    }
}

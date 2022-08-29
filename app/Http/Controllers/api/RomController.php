<?php

namespace App\Http\Controllers\api;

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
    public function index()
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
    public function store(StoreRomRequest $request)
    {
        $rom = Rom::create($request->all());

        return (new RomResource($rom))->response()->setStatusCode(HttpStatus::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function linkRomToRomFile(Rom $rom): JsonResponse
    {
        $this->authorize('update', $rom);
        AttemptRomLinkToRomFile::dispatchUnless($rom->has_file === TRUE, $rom);
        $rom->refresh();
        if ($rom->has_file === TRUE) {
            return Response::json([
                'message' => "file found and linked! file id: " . $rom->romFile->_id,
                'data' => $rom,
                'success' => true
            ], HttpStatus::HTTP_OK);
        } else return Response::json([
            'message' => "File not found with name of {$rom->getRomFileName()}",
            'success' => false
        ], HttpStatus::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     *
     * @param Rom $rom
     * @return RomResource
     */
    public function show(Rom $rom)
    {
        $rom = Rom::findOrFail($rom->id);
        return new RomResource($rom);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRomRequest $request
     * @param Rom $rom
     * @return RomResource
     */
    public function update(UpdateRomRequest $request, Rom $rom)
    {
        $rom->update($request->all());
        return new RomResource($rom);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rom $rom
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Rom $rom)
    {
        $this->authorize('delete', $rom);
        $rom->delete();
        return response()->json([
            'message' => "Rom deleted successfully! " . $rom->rom_name,
            'success' => true
        ], HttpStatus::HTTP_OK);
    }
}

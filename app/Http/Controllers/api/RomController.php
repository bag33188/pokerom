<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RomCollection;
use App\Http\Resources\RomResource;
use App\Models\Rom;
use Illuminate\Http\Request;

class RomController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rom  $rom
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rom $rom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rom $rom)
    {
        //
    }
}

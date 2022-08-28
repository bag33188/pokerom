<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRomRequest;
use App\Http\Requests\UpdateRomRequest;
use App\Interfaces\RomRepositoryInterface;
use App\Models\Rom;
use App\Models\RomFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(RomRepositoryInterface $romRepository)
    {
        $roms = Rom::with(['romFile', 'game'])->get();
        $tableColumns = ['ROM Name', 'ROM Size', 'ROM Type', 'Game Name', 'Download', 'Information'];
        return view('roms.index', [
            'roms' => $roms,
            'formatRomSize' => fn(int $rom_size): string => $romRepository->formatRomSizeSQL($rom_size),
            'tableColumns' => $tableColumns,
            'totalRomsSize' => RomFile::whereHas('rom')->sum('length')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('roms.create', ['romTypes' => ROM_TYPES]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRomRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRomRequest $request)
    {
        $rom = Rom::create($request->all());
        return response()->redirectTo(route('roms.index'))->banner('Rom created successfully! ' . $rom->rom_name);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Rom $rom
     * @return \Illuminate\Http\Response
     */
    public function show(Rom $rom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Rom $rom
     * @return \Illuminate\Http\Response
     */
    public function edit(Rom $rom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateRomRequest $request
     * @param \App\Models\Rom $rom
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRomRequest $request, Rom $rom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Rom $rom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rom $rom)
    {
        //
    }
}

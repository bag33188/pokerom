<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRomRequest;
use App\Http\Requests\UpdateRomRequest;
use App\Interfaces\RomFileQueriesInterface;
use App\Interfaces\RomQueriesInterface;
use App\Models\Rom;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param RomQueriesInterface $romQueries
     * @param RomFileQueriesInterface $romFileQueries
     * @return Application|Factory|View
     */
    public function index(RomQueriesInterface $romQueries, RomFileQueriesInterface $romFileQueries)
    {
        $roms = Rom::with(['romFile', 'game'])->get();
        return view('roms.index', [
            'roms' => $roms,
            'formatRomSize' => fn(int $rom_size): string => $romQueries->formatRomSizeSQL($rom_size),
            'tableColumns' => ['ROM Name', 'ROM Size', 'ROM Type', 'Game Name', 'Download', 'Information'],
            'totalRomsSize' => $romFileQueries->getTotalSizeOfAllFilesThatHaveRoms(),
            'totalRomsCount' => Rom::all()->count(),
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
     * @throws AuthorizationException
     */
    public function store(StoreRomRequest $request)
    {
        $this->authorize('create', Rom::class);
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

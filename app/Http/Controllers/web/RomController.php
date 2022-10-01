<?php

namespace App\Http\Controllers\Web;

use App\Events\AttemptRomLinkToRomFile;
use App\Http\Controllers\Controller as WebController;
use App\Http\Requests\StoreRomRequest;
use App\Http\Requests\UpdateRomRequest;
use App\Interfaces\RomFileQueriesInterface;
use App\Models\Rom;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RomController extends WebController
{
    public function __construct()
    {
        $this->middleware('admin')->only('create', 'edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @param RomFileQueriesInterface $romFileQueries
     * @return Application|Factory|View
     */
    public function index(RomFileQueriesInterface $romFileQueries)
    {
        $roms = Rom::with(['romFile', 'game'])->get();

        return view('roms.index', [
            'roms' => $roms,
            'htmlRomTableColumns' => ['ROM Name', 'ROM Size', 'ROM Type', 'Game Name', 'Download', 'Information'],
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
        return view('roms.create');
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
     * @param Rom $rom
     * @return Application|Factory|View
     */
    public function show(Rom $rom)
    {
        $alpineUiData = "{ romInfoOpened: true, gameInfoOpened: true, romFileInfoOpened: true }";
        return view('roms.show', [
            'rom' => $rom,
            'userIsAdmin' => Auth::user()->isAdmin(),
            'alpineInitialUiState' => html_entity_decode($alpineUiData),
            'gameNameTitle' => $rom->has_game === TRUE ? sprintf('title="%s"', $rom->game->game_name) : ''
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Rom $rom
     * @return Application|Factory|View
     */
    public function edit(Rom $rom)
    {
        return view('roms.edit', [
            'rom' => $rom
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRomRequest $request
     * @param Rom $rom
     * @return RedirectResponse
     */
    public function update(UpdateRomRequest $request, Rom $rom)
    {
        $rom->update($request->all());
        return response()->redirectTo(route('roms.index'))->banner('Rom updated successfully! ' . $rom->rom_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rom $rom
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Rom $rom)
    {
        $this->authorize('delete', $rom);
        Rom::destroy($rom->id);
        return response()->redirectTo(route('roms.index'))->banner('Rom deleted successfully! ' . $rom->rom_name);
    }

    public function linkFile(Rom $rom)
    {
        AttemptRomLinkToRomFile::dispatchUnless($rom->has_file === TRUE, $rom);
        return !empty($rom->fild_id)
            ? response()->redirectTo(route('roms.index'))->banner('ROM File linked successfully! ' . $rom->getRomFileName())
            : response()->redirectTo(route('roms.show', ['rom' => $rom]))->dangerBanner('ROM File link failed! ' . $rom->getRomFileName());
    }
}

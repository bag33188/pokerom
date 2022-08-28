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
    public function __construct(private readonly RomQueriesInterface $romQueries)
    {
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
            'formatRomSize' => fn(int $rom_size): string => $this->romQueries->formatRomSizeSQL($rom_size),
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
        return view('roms.create', [
            'romTypes' => ROM_TYPES,
            'formSelectClasses' => [
                'border-gray-300',
                'focus:border-indigo-300',
                'focus:ring',
                'focus:ring-indigo-200',
                'focus:ring-opacity-50',
                'rounded-md',
                'shadow-sm',
                'block',
                'mt-1',
                'w-full'
            ]
        ]);
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
        return view('roms.show', [
            'rom' => $rom,
            'formatRomSize' => fn(int $rom_size): string => $this->romQueries->formatRomSizeSQL($rom_size)
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
            'rom' => $rom,
            'romTypes' => ROM_TYPES
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
}

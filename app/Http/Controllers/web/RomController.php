<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRomRequest;
use App\Http\Requests\UpdateRomRequest;
use App\Models\Rom;
use App\Models\User;

class RomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('roms.index', ['roms' => Rom::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRomRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRomRequest $request)
    {
//        $request->validated();
        $rom = Rom::create($request->validated());
        return response()->redirectTo(route('roms.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Http\Response
     */
    public function show(Rom $rom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Http\Response
     */
    public function edit(Rom $rom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRomRequest  $request
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRomRequest $request, Rom $rom)
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

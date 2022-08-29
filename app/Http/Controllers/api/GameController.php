<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller as ApiController;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Game $game
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Game $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Game $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($game)
    {
        //
    }
}

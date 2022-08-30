<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller as ViewController;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Interfaces\GameQueriesInterface;
use App\Models\Game;
use App\Models\Rom;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class GameController extends ViewController
{
    public function __construct(private readonly GameQueriesInterface $gameQueries)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        return view('games.index', [
            'games' => Game::all(),
            'formatGameType' => fn(string $game_type): string => $this->gameQueries->formatGameTypeSQL($game_type),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('games.create', ['romsWithNoGame' => $this->gameQueries->getAllRomsWithNoGameSQL()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreGameRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreGameRequest $request)
    {
        $romId = $request['rom_id'];
        $rom = Rom::findOrFail($romId);
        $game = $rom->game()->create($request->all());
        return response()->redirectTo(route('games.index'))->banner('Game created successfully! ' . $game->game_name);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Game $game
     * @return Application|Factory|View
     */
    public function show(Game $game)
    {
        return view('games.show', [
            'game' => $game,
            'formatGameType' => fn(string $game_type): string => $this->gameQueries->formatGameTypeSQL($game_type),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Game $game
     * @return Application|Factory|View
     */
    public function edit(Game $game)
    {
        return view('games.edit', [
            'game' => $game
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateGameRequest $request
     * @param \App\Models\Game $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        $game->update($request->all());
        return response()->redirectTo(route('games.index'))->banner('Game updated successfully! ' . $game->game_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Game $game
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Game $game)
    {
        $this->authorize('delete', $game);
        $game->delete();
        return response()->redirectTo(route('games.index'))->banner('Game deleted successfully! ' . $game->game_name);
    }
}

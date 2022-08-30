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
use Illuminate\Http\RedirectResponse;

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
            'games' => Game::with('rom')->get(),
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
     * @param StoreGameRequest $request
     * @return RedirectResponse
     */
    public function store(StoreGameRequest $request)
    {
        $romId = $request->input('rom_id');
        $rom = Rom::findOrFail($romId);
        $game = $rom->game()->create($request->all());
        return response()->redirectTo(route('games.index'))->banner('Game created successfully! ' . $game->game_name);
    }

    /**
     * Display the specified resource.
     *
     * @param Game $game
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
     * @param Game $game
     * @return Application|Factory|View
     */
    public function edit(Game $game)
    {
        return view('games.edit', [
            'game' => $game,
            'removeEacute' => fn(string $value) => str_replace(_EACUTE, "\u{0065}", $value),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGameRequest $request
     * @param Game $game
     * @return RedirectResponse
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        $game->update($request->all());
        return response()->redirectTo(route('games.index'))->banner('Game updated successfully! ' . $game->game_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Game $game
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Game $game)
    {
        $this->authorize('delete', $game);
        $game->delete();
        return response()->redirectTo(route('games.index'))->banner('Game deleted successfully! ' . $game->game_name);
    }
}

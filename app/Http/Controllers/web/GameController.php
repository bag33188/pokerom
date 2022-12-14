<?php /** @noinspection PhpVoidFunctionResultUsedInspection */

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller as WebController;
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

class GameController extends WebController
{
    public function __construct()
    {
        $this->middleware('admin')->only(['create', 'edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $games = Game::with('rom')->get();

        return view('games.index', [
            'games' => $games,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param GameQueriesInterface $gameQueries
     * @return Application|Factory|View
     */
    public function create(GameQueriesInterface $gameQueries): Application|Factory|View
    {
        $romsWithNoGameRelationship = $gameQueries->getAllRomsWithNoGameSQL();
        return view('games.create', ['romsWithNoAssocGame' => $romsWithNoGameRelationship]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGameRequest $request
     * @return RedirectResponse
     */
    public function store(StoreGameRequest $request): RedirectResponse
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
    public function show(Game $game): Application|Factory|View
    {
        return view('games.show', [
            'game' => $game,
            'userIsAdmin' => auth()->user()->isAdmin(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Game $game
     * @return Application|Factory|View
     */
    public function edit(Game $game): Application|Factory|View
    {
        $replaceEacuteUnicodeCharWithTheLetterE = fn(string $value): string => str_replace(_EACUTE, "\u{0065}" /* the letter `e` */, $value);
        return view('games.edit', [
            'game' => $game,
            'replaceEacuteWithE' => $replaceEacuteUnicodeCharWithTheLetterE,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGameRequest $request
     * @param Game $game
     * @return RedirectResponse
     */
    public function update(UpdateGameRequest $request, Game $game): RedirectResponse
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
    public function destroy(Game $game): RedirectResponse
    {
        $this->authorize('delete', $game);
        $game->delete();
        return response()->redirectTo(route('games.index'))->banner('Game deleted successfully! ' . $game->game_name);
    }
}

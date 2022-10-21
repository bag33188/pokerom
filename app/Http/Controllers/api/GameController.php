<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as ApiController;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Rom;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class GameController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return GameCollection
     */
    public function index(): GameCollection
    {
        $games = Game::all();
        return new GameCollection($games);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGameRequest $request
     * @return JsonResponse
     */
    public function store(StoreGameRequest $request): JsonResponse
    {
        $romId = $request->input('rom_id');
        $rom = Rom::findOrFail($romId);
        $game = $rom->game()->create($request->all());
        return (new GameResource($game->load('rom')))->response()->setStatusCode(HttpStatus::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $gameId
     * @return GameResource
     */
    public function show(int $gameId): GameResource
    {
        $game = Game::findOrFail($gameId);
        return new GameResource($game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGameRequest $request
     * @param int $gameId
     * @return GameResource
     */
    public function update(UpdateGameRequest $request, int $gameId): GameResource
    {
        $game = Game::findOrFail($gameId);
        $game->update($request->all());
        return new GameResource($game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $gameId
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $gameId): JsonResponse
    {
        $game = Game::findOrFail($gameId);
        $this->authorize('delete', $game);
        Game::destroy($gameId);
        return response()->json([
            'message' => 'Game deleted successfully! ' . $game->game_name,
            'success' => true
        ], HttpStatus::HTTP_OK);
    }
}

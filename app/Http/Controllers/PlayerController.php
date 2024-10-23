<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Services\PlayerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * PlayerController constructor.
     *
     * @param PlayerService $playerRepository
     */
    public function __construct(protected PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Get all players.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 20);
        $name = $request->query('name');

        $players = $this->playerService->getAllPlayers($perPage, $name);

        return response()->json(PlayerResource::collection($players)->response()->getData(true));
    }

    /**
     * Show the details of a player by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $player = $this->playerService->getPlayerById($id);
        return response()->json(new PlayerResource($player));
    }


    /**
     * Create new Player
     * 
     * @param PlayerRequest $request
     * @return JsonResponse
     */
    public function store(PlayerRequest $request): JsonResponse
    {
        $player = $this->playerService->createPlayer($request->validated());
        return response()->json(new PlayerResource($player), 201);
    }

    /**
     * Update an existing player by ID.
     *
     * @param PlayerRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PlayerRequest $request, int $id): JsonResponse
    {
        $updatedPlayer = $this->playerService->updatePlayer($id, $request->validated());
        return response()->json(new PlayerResource($updatedPlayer));
    }

    /**
     * Delete a player by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->playerService->deletePlayer($id);
            return response()->json(['message' => 'Player deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the player'], 500);
        }
    }
}

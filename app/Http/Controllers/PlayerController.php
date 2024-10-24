<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Services\PlayerService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Sports Manager API",
 *     version="1.0.0",
 *     description="API para gerenciar dados esportivos, incluindo jogadores, times e jogos.",
 * )
 * 
 *  @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
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
     * 
     * @OA\Get(
     *     path="/api/players",
     *     summary="Lista todos os jogadores",
     *     tags={"Players"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de jogadores retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="first_name", type="string", example="Jaylen"),
     *                 @OA\Property(property="last_name", type="string", example="Adams"),
     *                 @OA\Property(property="position", type="string", example="G"),
     *                 @OA\Property(property="height", type="string", example="6-0"),
     *                 @OA\Property(property="weight", type="string", example="225"),
     *                 @OA\Property(property="college", type="string", example="St. Bonaventure"),
     *                 @OA\Property(property="country", type="string", example="USA"),
     *                 @OA\Property(
     *                     property="team",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="conference", type="string", example="East"),
     *                     @OA\Property(property="division", type="string", example="Southeast"),
     *                     @OA\Property(property="city", type="string", example="Atlanta"),
     *                     @OA\Property(property="name", type="string", example="Hawks"),
     *                     @OA\Property(property="full_name", type="string", example="Atlanta Hawks"),
     *                     @OA\Property(property="abbreviation", type="string", example="ATL")
     *                 )
     *             )
     *         )
     *     )
     * )
     * 
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
     * 
     * 
     * @OA\Post(
     *     path="/api/players",
     *     summary="Cria um novo jogador",
     *     tags={"Players"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "position", "team_id"},
     *             @OA\Property(property="first_name", type="string", example="Fred"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="position", type="string", example="F"),
     *             @OA\Property(property="height", type="string", example="6.5"),
     *             @OA\Property(property="weight", type="string", example="220"),
     *             @OA\Property(property="college", type="string", example="Duke"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="team_id", type="integer", example="2")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jogador criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="position", type="string", example="Forward"),
     *             @OA\Property(property="height", type="string", example="6.5"),
     *             @OA\Property(property="weight", type="string", example="220"),
     *             @OA\Property(property="college", type="string", example="Duke"),
     *             @OA\Property(property="country", type="string", example="USA")
     *         )
     *     )
     * )
     *
     *
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
     * 
     * @OA\Put(
     *     path="/api/players/{id}",
     *     summary="Atualiza um jogador existente",
     *     tags={"Players"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogador a ser atualizado",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "position"},
     *             @OA\Property(property="first_name", type="string", example="Jane"),
     *             @OA\Property(property="last_name", type="string", example="Smith"),
     *             @OA\Property(property="position", type="string", example="Guard"),
     *             @OA\Property(property="height", type="string", example="5.9"),
     *             @OA\Property(property="weight", type="string", example="150"),
     *             @OA\Property(property="college", type="string", example="UCLA"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="team_id", type="integer", example="3")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jogador atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="first_name", type="string", example="Jane"),
     *             @OA\Property(property="last_name", type="string", example="Smith"),
     *             @OA\Property(property="position", type="string", example="Guard"),
     *             @OA\Property(property="height", type="string", example="5.9"),
     *             @OA\Property(property="weight", type="string", example="150"),
     *             @OA\Property(property="college", type="string", example="UCLA"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="team_id", type="integer", example=3)
     *         )
     *     )
     * )
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
     * 
     * @OA\Delete(
     *     path="/api/players/{id}",
     *     summary="Deleta um jogador existente",
     *     tags={"Players"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogador a ser deletado",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jogador deletado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jogador não encontrado"
     *     )
     * )
     * 
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

<?php

namespace App\Services;

use App\Models\Player;
use App\Repositories\PlayerRepositoryInterface;
use App\Traits\HandlesExceptions;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class PlayerService
{

    use HandlesExceptions;
    
    /**
     * PlayerController constructor.
     *
     * @param PlayerRepositoryInterface $playerRepository
     */
    public function __construct(protected PlayerRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    /**
     * Get all players with pagination.
     *
     * @param int $perPage
     * @param string|null $search
     * @return Paginator
     */
    public function getAllPlayers(int $perPage = 20, ?string $name = null): Paginator
    {
        return $this->playerRepository->all($perPage, $name);
    }

    /**
     * Get a player by ID.
     *
     * @param int $id
     * @return Player
     * @throws ModelNotFoundException
     */
    public function getPlayerById(int $id): Player
    {
        try {
            return $this->playerRepository->find($id);
        } catch (ModelNotFoundException $e) {
            $this->handleModelNotFound($id, "Player not found");
        }
    }

    /**
     * Create a new player.
     *
     * @param array $data
     * @return Player
     */
    public function createPlayer(array $data): Player
    {
        return $this->playerRepository->create($data);
    }

    /**
     * Update an existing player.
     *
     * @param int $id
     * @param array $data
     * @return Player
     * @throws ModelNotFoundException
     */
    public function updatePlayer(int $id, array $data): Player
    {
        try {
            return $this->playerRepository->update($id, $data);
        } catch (ModelNotFoundException $e) {
            $this->handleModelNotFound($id, "Player not found");
        }
    }

    /**
     * Delete a player by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deletePlayer(int $id): bool
    {
        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            $this->handleUnauthorized("You do not have permission to delete players.");
        }
        
        try {
            return $this->playerRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            $this->handleModelNotFound($id, "Player not found");
        }
    }

    
}

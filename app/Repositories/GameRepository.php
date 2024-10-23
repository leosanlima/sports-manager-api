<?php

namespace App\Repositories;

use App\Models\Game;
use Illuminate\Contracts\Pagination\Paginator;

class GameRepository implements GameRepositoryInterface
{
    /**
     * Get all games.
     * 
     * @param int $perPage
     * @return Paginator
     */
    public function all(int $perPage = 20): Paginator
    {
        return Game::simplePaginate($perPage);
    }

    /**
     * Find a Game by ID.
     *
     * @param int $id
     * @return Game
     */
    public function find(int $id): Game
    {
        return Game::findOrFail($id);
    }

    /**
     * Create a new Game.
     *
     * @param array $data
     * @return Game
     */
    public function create(array $data): Game
    {
        return Game::create($data);
    }

    /**
     * Update an existing Game.
     *
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function update(Game $game, array $data): Game
    {
        $game->update($data);
        return $game;
    }

    /**
     * Delete a Game.
     *
     * @param Game $game
     * @return bool|null
     */
    public function delete(Game $game): ?bool
    {
        return $game->delete();
    }
}

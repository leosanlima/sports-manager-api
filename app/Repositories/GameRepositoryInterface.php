<?php

namespace App\Repositories;

use App\Models\Game;
use Illuminate\Contracts\Pagination\Paginator;

interface GameRepositoryInterface
{
    /**
     * Get all Game.
     *
     * @return Paginator|static[]
     */
    public function all(): Paginator;

    /**
     * Find a Game by ID.
     *
     * @param int $id
     * @return Game
     */
    public function find(int $id): Game;

    /**
     * Create a new Game.
     *
     * @param array $data
     * @return Game
     */
    public function create(array $data): Game;

    /**
     * Update an existing Game.
     *
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function update(Game $game, array $data): Game;

    /**
     * Delete a Game.
     *
     * @param Game $game
     * @return bool|null
     */
    public function delete(Game $game): ?bool;
}
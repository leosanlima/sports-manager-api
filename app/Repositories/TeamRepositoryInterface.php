<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Contracts\Pagination\Paginator;

interface TeamRepositoryInterface
{
    /**
     * Get all Teams.
     *
     * @return Paginator|static[]
     */
    public function all(): Paginator;

    /**
     * Find a Team by ID.
     *
     * @param int $id
     * @return Team
     */
    public function find(int $id): Team;

    /**
     * Create a new Team.
     *
     * @param array $data
     * @return Team
     */
    public function create(array $data): Team;

    /**
     * Update an existing Team.
     *
     * @param Team $team
     * @param array $data
     * @return Team
     */
    public function update(Team $team, array $data): Team;

    /**
     * Delete a Team.
     *
     * @param Team $team
     * @return bool|null
     */
    public function delete(Team $team): ?bool;
}
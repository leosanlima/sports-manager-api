<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class TeamRepository implements TeamRepositoryInterface
{
    /**
     * Get all team.
     * 
     * @param int $perPage
     * @return Paginator
     */
    public function all(int $perPage = 20, ?string $search = null): Paginator
    {
        $query = Team::query();

        if ($search) {
            $columns = Schema::getColumnListing('teams');

            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        return $query->simplePaginate($perPage);
    }

    /**
     * Find a Team by ID.
     *
     * @param int $id
     * @return Team
     */
    public function find(int $id): Team
    {
        return Team::findOrFail($id);
    }

    /**
     * Create a new Team.
     *
     * @param array $data
     * @return Team
     */
    public function create(array $data): Team
    {
        return Team::create($data);
    }

    /**
     * Update an existing Team.
     *
     * @param Team $Team
     * @param array $data
     * @return Team
     */
    public function update(Team $team, array $data): Team
    {
        $team->update($data);
        return $team;
    }

    /**
     * Delete a Team.
     *
     * @param Team $Team
     * @return bool|null
     */
    public function delete(Team $team): ?bool
    {
        return $team->delete();
    }
}

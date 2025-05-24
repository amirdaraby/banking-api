<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    /**
     * Get all records with optional filtering and sorting
     */
    public function list(
        array $columns = ['*'],
        array $relations = [],
        ?string $orderBy = null,
        string $orderDirection = 'asc'
    ): Collection {
        return $this->buildBaseQuery($columns, $relations, $orderBy, $orderDirection)
            ->get();
    }

    /**
     * Get paginated records with optional filtering and sorting
     */
    public function listPaginated(
        array $columns = ['*'],
        array $relations = [],
        int $perPage = 50,
        ?string $orderBy = null,
        string $orderDirection = 'asc'
    ): LengthAwarePaginator {
        return $this->buildBaseQuery($columns, $relations, $orderBy, $orderDirection)
            ->paginate($perPage);
    }

    /**
     * Find a record by ID or throw exception
     */
    public function findOrFailById(
        int $id,
        array $columns = ['*'],
        array $relations = []
    ): Model {
        return $this->buildBaseQuery($columns, $relations)
            ->findOrFail($id);
    }

    /**
     * Find a record by ID or return null
     */
    public function findOrNullById(
        int $id,
        array $columns = ['*'],
        array $relations = []
    ): ?Model {
        return $this->buildBaseQuery($columns, $relations)
            ->find($id);
    }

    /**
     * Create a new record
     */
    public function create(array $attributes): Model
    {
        return $this->model->newQuery()
            ->create($attributes);
    }

    /**
     * Update a record by ID with lock
     */
    public function updateById(int $id, array $attributes = []): bool
    {
        return $this->model->newQuery()
            ->findOrFail($id)
            ->lockForUpdate()
            ->update($attributes);
    }

    /**
     * Delete a record by ID
     */
    public function deleteById(int $id): bool
    {
        return $this->model->newQuery()
            ->findOrFail($id)
            ->delete();
    }

    /**
     * Build base query with common options
     */
    protected function buildBaseQuery(
        array $columns = ['*'],
        array $relations = [],
        ?string $orderBy = null,
        string $orderDirection = 'asc'
    ): Builder {
        return $this->model->newQuery()
            ->select($columns)
            ->with($relations)
            ->when($orderBy, fn (Builder $query) => $query->orderBy($orderBy, $orderDirection));
    }
}

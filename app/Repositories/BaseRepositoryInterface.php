<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function list(array $columns = ["*"], array $relations = [], string $orderBy = null, string $orderByDirection = 'asc');

    public function listPaginated(array $columns = ["*"], array $relations = [], int $perPage = 50, string $orderBy = null, string $orderByDirection = 'asc');

    public function findOrFailById(int $id, array $columns = ["*"], array $relations = []);

    public function findOrNullById(int $id, array $columns = ["*"], array $relations = []);

    public function create(array $attributes);

    public function updateById(int $id, array $attributes);

    public function deleteById(int $id);
}

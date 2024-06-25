<?php

namespace App\Repositories\Transaction;

use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{

    public function listByUserId(int $userId, array $columns = ["*"], array $relations = [], int $take = 10, string $orderBy = null, string $orderByDirection = 'asc');

}

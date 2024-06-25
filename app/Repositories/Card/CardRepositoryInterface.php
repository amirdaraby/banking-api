<?php

namespace App\Repositories\Card;

use App\Repositories\BaseRepositoryInterface;

interface CardRepositoryInterface extends BaseRepositoryInterface
{
    public function listByAccountId(int $accountId, array $columns = ['*'], array $relations = []);

    public function findOrNullByNumber(string $number, array $columns = ['*'], array $relations = []);

}

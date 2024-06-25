<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{

    public function listTopUsers(int $limit = 3, array $columns = ["*"], array $relations = []);

}

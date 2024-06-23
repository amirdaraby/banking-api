<?php

namespace App\Repositories\Account;

use App\Models\Account;
use App\Repositories\BaseRepository;


class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }
}

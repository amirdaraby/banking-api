<?php

namespace App\Repositories\TransactionCost;

use App\Models\TransactionCost;
use App\Repositories\BaseRepository;

class TransactionCostRepository extends BaseRepository implements TransactionCostRepositoryInterface
{
    public function __construct(TransactionCost $model)
    {
        parent::__construct($model);
    }
}

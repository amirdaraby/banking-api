<?php

namespace App\Repositories\Card;

use App\Models\Card;
use App\Repositories\BaseRepository;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{
    public function __construct(Card $model)
    {
        parent::__construct($model);
    }

    public function listByAccountId(int $accountId, array $columns = ['*'], array $relations = [])
    {
        return $this->model->query()->select($columns)->with($relations)->where('account_id', '=', $accountId)->get();
    }

    public function findOrNullByNumber(string $number, array $columns = ['*'], array $relations = [])
    {
        return $this->model->query()->select($columns)->with($relations)->where('number', '=', $number)->first();
    }
}

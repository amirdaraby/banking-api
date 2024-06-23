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
}

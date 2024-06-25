<?php

namespace App\Repositories\Transaction;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function listByUserId(int $userId, array $columns = ["transactions.*"], array $relations = [], int $take = 10, string $orderBy = null, string $orderByDirection = 'asc')
    {
        return $this->model->query()
            ->select($columns)
            ->join('cards', 'transactions.card_id', '=', 'cards.id')
            ->join('accounts', 'cards.account_id', '=', 'accounts.id')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->leftJoin('transaction_costs', 'transaction_costs.transaction_id', '=', 'transactions.id')
            ->with($relations)
            ->when($orderBy, fn(Builder $query) => $query->orderBy($orderBy, $orderByDirection))
            ->where('users.id', '=', $userId)
            ->where('transactions.status', '=', TransactionStatus::SUCCESS->value)
            ->take($take)
            ->get();
    }
}

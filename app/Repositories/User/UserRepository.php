<?php

namespace App\Repositories\User;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function listTopUsers(int $limit = 3, array $columns = ["users.*"], array $relations = [])
    {
        return $this->model->query()
            ->join('accounts', 'users.id', '=', 'accounts.user_id')
            ->join('cards', 'cards.account_id', '=', 'accounts.id')
            ->join('transactions', 'transactions.card_id', '=', 'cards.id')
            ->whereDate('transactions.created_at', '>=', now()->subMinutes(10)->carbonize())
            ->where('transactions.status', '=', TransactionStatus::SUCCESS->value)
            ->select($columns)
            ->selectRaw("COUNT(transactions.id) as transactions_count")
            ->groupBy('users.id')
            ->orderBy('transactions_count', 'desc')
            ->with($relations)
            ->limit($limit)
            ->get();
    }
}

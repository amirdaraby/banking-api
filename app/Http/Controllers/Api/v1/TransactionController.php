<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Events\CardToCardSuccessEvent;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;
use App\Http\Requests\Transaction\CardToCardRequest;
use App\Http\Resources\Transaction\TopUsers\UserResource;
use App\Repositories\Card\CardRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\TransactionCost\TransactionCostRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionRepositoryInterface     $transactionRepository,
        protected CardRepositoryInterface            $cardRepository,
        protected TransactionCostRepositoryInterface $transactionCostRepository,
        protected UserRepositoryInterface            $userRepository,
    )
    {
    }

    public function cardToCard(CardToCardRequest $request): JsonResponse
    {
        if ($request->validated('source_card') == $request->validated('destination_card')) {
            return ResponseJson::error(__('card_to_card.same_cards'));
        }

        $sourceCard = $this->cardRepository->findOrNullByNumber($request->validated('source_card'), relations: ['account', 'account.user']);

        if (is_null($sourceCard)) {
            return ResponseJson::error(__('card_to_card.card_not_found', ['number' => $request->validated('source_card')]), Response::HTTP_NOT_FOUND);
        }

        $destinationCard = $this->cardRepository->findOrNullByNumber($request->validated('destination_card'), relations: ['account', 'account.user']);

        if (is_null($destinationCard)) {
            return ResponseJson::error(__('card_to_card.card_not_found', ['number' => $request->validated('destination_card')]), Response::HTTP_NOT_FOUND);
        }

        $transactionCost = config('banking.card_to_card.transaction_cost');

        $amount = $request->validated('amount');

        $balance = $sourceCard->account->balance;
        $amountAndTransactionCost = $amount + $transactionCost;

        if ($balance < $amountAndTransactionCost) {
            return ResponseJson::error(__('card_to_card.insufficient_balance', ['your' => $balance, 'needed' => $amountAndTransactionCost]));
        }

        $sendingTransaction = $this->transactionRepository->create([
            'amount' => $amount,
            'card_id' => $sourceCard->id,
            'type' => TransactionDirection::SEND,
            'status' => TransactionStatus::INIT,
        ]);

        try {
            DB::beginTransaction();

            $sourceCard->account()->lockForUpdate()->update([
                'balance' => DB::raw('balance - ' . $amountAndTransactionCost),
            ]);

            $destinationCard->account()->lockForUpdate()->update([
                'balance' => DB::raw('balance + ' . $amount),
            ]);


            $this->transactionCostRepository->create(['amount' => $transactionCost, 'transaction_id' => $sendingTransaction->id]);

            $sendingTransaction->update(['status' => TransactionStatus::SUCCESS]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $sendingTransaction->update(['status' => TransactionStatus::FAILED]);

            return ResponseJson::error(__('card_to_card.failed'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $receivingTransaction = $this->transactionRepository->create([
            'amount' => $amount,
            'card_id' => $destinationCard->id,
            'type' => TransactionDirection::RECEIVE,
            'status' => TransactionStatus::SUCCESS,
        ]);

        event(new CardToCardSuccessEvent($receivingTransaction, $sendingTransaction));

        return ResponseJson::success(['send_transaction' => $sendingTransaction, 'received_transaction' => $receivingTransaction], __('card_to_card.success'), Response::HTTP_OK);
    }

    public function topUsers(): JsonResponse
    {
        $topUsers = $this->userRepository->listTopUsers();

        if ($topUsers->isEmpty()) {
            return ResponseJson::error(__('top_users.not_found'), Response::HTTP_NOT_FOUND);
        }

        $topUsers->map(function ($user) {
            $user->last_transactions = $this->transactionRepository->listByUserId($user->id, ['transactions.*', 'transaction_costs.amount as transaction_cost_amount'], orderBy: 'transactions.created_at', orderByDirection: 'desc');
        });

        return ResponseJson::success(UserResource::collection($topUsers), __('top_users.success'), Response::HTTP_OK);
    }
}

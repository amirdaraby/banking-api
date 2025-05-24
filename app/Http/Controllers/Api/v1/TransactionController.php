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
use Throwable;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected CardRepositoryInterface $cardRepository,
        protected TransactionCostRepositoryInterface $transactionCostRepository,
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    public function cardToCard(CardToCardRequest $request): JsonResponse
    {
        $sourceCardNumber = $request->validated('source_card');
        $destinationCardNumber = $request->validated('destination_card');
        $amount = $request->validated('amount');

        // Validate same cards
        if ($sourceCardNumber === $destinationCardNumber) {
            return ResponseJson::error(__('card_to_card.same_cards'));
        }

        // Find cards with necessary relations
        $sourceCard = $this->getCardOrFail($sourceCardNumber);
        $destinationCard = $this->getCardOrFail($destinationCardNumber);

        // Calculate transaction details
        $transactionCost = config('banking.card_to_card.transaction_cost');
        $amountWithCost = $amount + $transactionCost;

        // Check balance
        if ($sourceCard->account->balance < $amountWithCost) {
            return ResponseJson::error(__('card_to_card.insufficient_balance', [
                'your' => $sourceCard->account->balance,
                'needed' => $amountWithCost
            ]));
        }

        // Create initial transaction
        $sendingTransaction = $this->transactionRepository->create([
            'amount' => $amount,
            'card_id' => $sourceCard->id,
            'type' => TransactionDirection::SEND,
            'status' => TransactionStatus::INIT,
        ]);

        try {
            DB::transaction(function () use (
                $sourceCard,
                $destinationCard,
                $amount,
                $amountWithCost,
                $transactionCost,
                $sendingTransaction
            ) {
                // Update balances
                $sourceCard->account()->lockForUpdate()->decrement('balance', $amountWithCost);
                $destinationCard->account()->lockForUpdate()->increment('balance', $amount);

                // Record transaction cost
                $this->transactionCostRepository->create([
                    'amount' => $transactionCost,
                    'transaction_id' => $sendingTransaction->id
                ]);

                // Update transaction status
                $sendingTransaction->update(['status' => TransactionStatus::SUCCESS]);
            });
        } catch (Throwable $e) {
            $sendingTransaction->update(['status' => TransactionStatus::FAILED]);
            return ResponseJson::error(
                __('card_to_card.failed'),
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        // Create receiving transaction
        $receivingTransaction = $this->transactionRepository->create([
            'amount' => $amount,
            'card_id' => $destinationCard->id,
            'type' => TransactionDirection::RECEIVE,
            'status' => TransactionStatus::SUCCESS,
        ]);

        // Fire success event
        event(new CardToCardSuccessEvent($receivingTransaction, $sendingTransaction));

        return ResponseJson::success([
            'send_transaction' => $sendingTransaction,
            'received_transaction' => $receivingTransaction
        ], __('card_to_card.success'));
    }

    public function topUsers(): JsonResponse
    {
        $topUsers = $this->userRepository->listTopUsers();

        if ($topUsers->isEmpty()) {
            return ResponseJson::error(
                __('top_users.not_found'),
                Response::HTTP_NOT_FOUND
            );
        }

        $topUsers->each(function ($user) {
            $user->last_transactions = $this->transactionRepository->listByUserId(
                $user->id,
                ['transactions.*', 'transaction_costs.amount as transaction_cost_amount'],
                'transactions.created_at',
                'desc'
            );
        });

        return ResponseJson::success(
            UserResource::collection($topUsers),
            __('top_users.success')
        );
    }

    /**
     * Get card by number or return error response
     */
    protected function getCardOrFail(string $cardNumber)
    {
        $card = $this->cardRepository->findOrNullByNumber(
            $cardNumber,
            relations: ['account', 'account.user']
        );

        if (is_null($card)) {
            abort(ResponseJson::error(
                __('card_to_card.card_not_found', ['number' => $cardNumber]),
                Response::HTTP_NOT_FOUND
            ));
        }

        return $card;
    }
}

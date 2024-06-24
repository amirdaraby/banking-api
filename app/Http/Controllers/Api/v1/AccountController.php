<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;
use App\Http\Requests\Account\StoreRequest;
use App\Http\Requests\Account\UpdateRequest;
use App\Http\Resources\Account\ListResource;
use App\Http\Resources\Account\ShowResource;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Card\CardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function __construct(
        protected AccountRepositoryInterface $accountRepository,
        protected CardRepositoryInterface    $cardRepository,
    )
    {
    }

    public function index(): JsonResponse
    {
        $accounts = $this->accountRepository->listPaginated();

        if ($accounts->isEmpty()) {
            return ResponseJson::error(__('accounts.index.not_found'), Response::HTTP_NOT_FOUND);
        }

        return ResponseJson::success(ListResource::collection($accounts)->resource, __('accounts.index.success'), Response::HTTP_OK);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $account = $this->accountRepository->create($request->validated());

        return ResponseJson::success(ShowResource::make($account), __('accounts.store.success'), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $account = $this->accountRepository->findOrFailById($id, ['id', 'user_id', 'number', 'balance'], ['user']);

        $account->cards = $this->cardRepository->listByAccountId($account->id);

        return ResponseJson::success(ShowResource::make($account), __('accounts.show.success'), Response::HTTP_OK);
    }

    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $updated = $this->accountRepository->updateById($id, $request->validated());

        return ResponseJson::success(['updated' => $updated], __('accounts.update.success'), Response::HTTP_ACCEPTED);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->accountRepository->deleteById($id);

        if (!$deleted) {
            return ResponseJson::error(__('accounts.delete.failed'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return ResponseJson::success(['deleted' => $deleted], __('accounts.delete.success'), Response::HTTP_ACCEPTED);
    }
}

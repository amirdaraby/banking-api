<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;
use App\Http\Requests\Card\StoreRequest;
use App\Http\Requests\Card\UpdateRequest;
use App\Http\Resources\Card\ListResource;
use App\Http\Resources\Card\ShowResource;
use App\Repositories\Card\CardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CardController extends Controller
{
    public function __construct(protected CardRepositoryInterface $cardRepository)
    {
    }

    public function index(): JsonResponse
    {
        $cards = $this->cardRepository->listPaginated(['id', 'account_id', 'number', 'expiration_year', 'expiration_month', 'cvv2'], orderBy: 'created_at', orderByDirection: 'desc');

        if ($cards->isEmpty()) {
            return ResponseJson::error(__('cards.index.not_found'), Response::HTTP_NOT_FOUND);
        }

        return ResponseJson::success(ListResource::collection($cards), __('cards.index.success'), Response::HTTP_OK);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $card = $this->cardRepository->create($request->validated());

        return ResponseJson::success(ShowResource::make($card), __('cards.store.success'), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $card = $this->cardRepository->findOrFailById($id);

        return ResponseJson::success(ShowResource::make($card), __('cards.show.success'), Response::HTTP_OK);
    }

    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $updated = $this->cardRepository->updateById($id, $request->validated());

        if (!$updated) {
            return ResponseJson::error(__('cards.update.failed'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return ResponseJson::success(['updated' => $updated], __('cards.update.success'), Response::HTTP_ACCEPTED);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->cardRepository->deleteById($id);

        if (!$deleted) {
            return ResponseJson::error(__('cards.delete.failed'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return ResponseJson::success(['deleted' => $deleted], __('cards.delete.success'), Response::HTTP_ACCEPTED);
    }
}

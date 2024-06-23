<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseJson;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\ListResource;
use App\Http\Resources\User\ShowResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function index(): JsonResponse
    {
        $users = $this->userRepository->listPaginated(["id", "name", "phone_number"], orderBy: "created_at", orderByDirection: "desc");

        if ($users->isEmpty()) {
            return ResponseJson::error(__('users.index.not_found'), Response::HTTP_NOT_FOUND);
        }

        return ResponseJson::success(ListResource::collection($users)->resource, __('users.index.success'));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());

        return ResponseJson::success(ShowResource::make($user), __('users.store.success'), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->findOrFailById($id, ["id", "name", "phone_number"]);

        return ResponseJson::success(ShowResource::make($user), __('users.show.success'), Response::HTTP_OK);
    }

    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $updated = $this->userRepository->updateById($id, $request->validated());

        if (!$updated) {
            return ResponseJson::error(__('users.update.failed'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return ResponseJson::success(['updated' => $updated], __('users.update.success'), Response::HTTP_ACCEPTED);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->userRepository->deleteById($id);

        if (!$deleted) {
            return ResponseJson::error(__('users.delete.failed'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return ResponseJson::success(['deleted' => $deleted], __('users.delete.success'), Response::HTTP_ACCEPTED);
    }
}

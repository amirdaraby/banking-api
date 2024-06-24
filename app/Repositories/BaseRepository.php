<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    /**
     * @param array $columns
     * @param array $relations
     * @param string|null $orderBy
     * @param string $orderByDirection
     * @return array|Builder[]|Collection|HigherOrderBuilderProxy[]
     */
    public function list(array $columns = ["*"], array $relations = [], string $orderBy = null, string $orderByDirection = 'asc')
    {
        return $this->model->query()->select($columns)->with($relations)
            ->when($orderBy, fn(Builder $query) => $query->orderBy($orderBy, $orderByDirection))
            ->get();
    }


    /**
     * @param array $columns
     * @param array $relations
     * @param int $perPage
     * @param string|null $orderBy
     * @param string $orderByDirection
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listPaginated(array $columns = ["*"], array $relations = [], int $perPage = 50, string $orderBy = null, string $orderByDirection = 'asc')
    {
        return $this->model->query()->select($columns)->with($relations)
            ->when($orderBy, fn(Builder $query) => $query->orderBy($orderBy, $orderByDirection))
            ->paginate($perPage);
    }


    /**
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findOrFailById(int $id, array $columns = ["*"], array $relations = [])
    {
        return $this->model->query()->select($columns)->with($relations)->findOrFail($id);
    }

    /**
     * @param int $id
     * @param array $columns
     * @param array $relations
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findOrNullById(int $id, array $columns = ["*"], array $relations = [])
    {
        return $this->model->query()->select($columns)->with($relations)->find($id);
    }

    /**
     * @param array $attributes
     * @return Builder|Model
     */
    public function create(array $attributes)
    {
        return $this->model->query()->create($attributes);
    }

    /**
     * @param int $id
     * @param array $attributes
     * @return bool|int
     */
    public function updateById(int $id, array $attributes = [])
    {
        return $this->model->query()->findOrFail($id)->lockForUpdate()->update($attributes);
    }

    /**
     * @param int $id
     * @return bool|mixed|null
     */
    public function deleteById(int $id)
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

}

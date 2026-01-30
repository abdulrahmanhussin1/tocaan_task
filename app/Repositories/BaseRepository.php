<?php

namespace App\Repositories;

use App\DTOs\FilterDto;
use App\Exceptions\General\NotFoundItemException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

abstract class BaseRepository implements RepositoryInterface
{
    public function __construct(protected Model $model) {}

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function get(array $conditions, array $columns = ['*']): Collection
    {
        return $this->query()->select($columns)->where($conditions)->get();
    }

    /**
     * @throws NotFoundItemException
     */
    public function find(int $id): ?Model
    {
        $row = $this->model->find($id);

        if (! $row) {
            throw new NotFoundItemException("Model with ID $id not found");
        }

        return $row;
    }

    public function create(array|object $data): Model
    {
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        } elseif (is_object($data)) {
            $data = (array) $data;
        }

        return $this->model->create($data);
    }

    /**
     * @throws NotFoundItemException
     */
    public function update(int $id, array|object $data): Model
    {
        $model = $this->find($id);

        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        } elseif (is_object($data)) {
            $data = (array) $data;
        }

        $model->update($data);

        return $model->refresh();
    }

    /**
     * @throws NotFoundItemException
     */
    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function paginateFiltered(FilterDto $dto, array $relations = [], array $conditions = []): LengthAwarePaginator
    {
        $query = $this->query()->with($relations);

        foreach ($conditions as $field => $value) {
            if ($value !== null) {
                $query->where($field, $value);
            }
        }

        $sort = $dto->sort();
        foreach ($sort as $column => $direction) {
            if (Schema::hasColumn($this->model->getTable(), $column)) {
                $query->orderBy($column, $direction);
            }
        }

        return $query->paginate($dto->perPage ?? 15);
    }

    public function first(array $conditions, array $columns = ['*']): ?Model
    {
        return $this->query()->select($columns)->where($conditions)->first();
    }
}

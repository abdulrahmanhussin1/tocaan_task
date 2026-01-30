<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\DTOs\FilterDto;

interface RepositoryInterface
{
    public function all(): Collection;
    public function get(array $conditions, array $columns = ['*']): Collection;
    public function find(int $id): ?Model;
    public function create(array|object $data): Model;
    public function update(int $id, array|object $data): Model;
    public function delete(int $id): bool;
    public function paginateFiltered(FilterDto $dto, array $relations = [], array $conditions = []): LengthAwarePaginator;
}

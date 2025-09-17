<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface BaseRepositoryInterface
 */
interface BaseRepositoryInterface
{
    /**
     * Find a model by ID.
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Create a new model instance with the given data.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update the given model with the provided data.
     *
     * @param Model $model
     * @param array $data
     * @return Model
     */
    public function update(Model $model, array $data): Model;

    /**
     * Delete the given model.
     *
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool;

    /**
     * Paginate models using optional filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}

<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BaseRepository
 *
 * Provides common Eloquent repository operations.
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Cache TTL in seconds.
     *
     * @var int
     */
    protected int $ttl;

    public function __construct()
    {
        // Configurable TTL; defaults to 60 seconds via config/cache.php: 'ttl' => env('CACHE_TTL', 60)
        $this->ttl = (int) config('cache.ttl', 60);
    }

    /**
     * Return the model class FQN handled by this repository.
     *
     * @return class-string<Model>
     */
    abstract protected function getModelClass(): string;

    /**
     * Make a new Eloquent query builder for the model.
     */
    protected function newQuery(): Builder
    {
        $modelClass = $this->getModelClass();
        /** @var Builder $query */
        $query = $modelClass::query();
        return $query;
    }

    /**
     * Find a model by ID.
     *
     * Caches the model instance by ID using a versioned namespace key.
     */
    public function find(int $id): ?Model
    {
        $key = $this->getCacheKey("find_{$id}");
        $modelClass = $this->getModelClass();

        return Cache::remember($key, $this->ttl, function () use ($modelClass, $id) {
            return $modelClass::find($id);
        });
    }

    /**
     * Create a new model instance with the given data.
     */
    public function create(array $data): Model
    {
        $modelClass = $this->getModelClass();
        /** @var Model $model */
        $model = $modelClass::create($data);
        // Invalidate cache by bumping namespace version
        $this->clearCache();
        return $model;
    }

    /**
     * Update the given model with the provided data.
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        // Invalidate cache by bumping namespace version
        $this->clearCache();
        return $model->fresh();
    }

    /**
     * Delete the given model.
     */
    public function delete(Model $model): bool
    {
        $deleted = (bool) $model->delete();
        // Invalidate cache by bumping namespace version
        $this->clearCache();
        return $deleted;
    }

    /**
     * Paginate models using optional filters.
     *
     * Caches the paginated result using a key that includes per-page and md5 of filters.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $filtersHash = md5((string) json_encode($filters));
        $key = $this->getCacheKey("paginate_{$perPage}_{$filtersHash}");

        return Cache::remember($key, $this->ttl, function () use ($filters, $perPage) {
            $query = $this->newQuery();
            $this->applyFilters($query, $filters);
            return $query->paginate($perPage);
        });
    }

    /**
     * Apply repository-specific filters to the query.
     *
     * @param Builder $query
     * @param array $filters
     */
    protected function applyFilters($query, array $filters): void
    {
        // Default: no filters. Children may override.
    }

    /**
     * Generate a unique cache key using model short name, namespace version and a suffix.
     *
     * Example: user_3_find_5
     */
    protected function getCacheKey(string $suffix): string
    {
        $modelShort = $this->getModelShortName();
        $version = $this->getNamespaceVersion();
        return strtolower($modelShort) . "_{$version}_{$suffix}";
    }

    /**
     * Increment the namespace version to invalidate previous cache entries for this model.
     */
    protected function clearCache(): void
    {
        $key = $this->getNamespaceVersionKey();
        // Use atomic increment; initialize if missing
        if (!Cache::has($key)) {
            Cache::forever($key, 1);
        }
        Cache::increment($key);
    }

    /**
     * Get the current namespace version for this model.
     */
    protected function getNamespaceVersion(): int
    {
        $key = $this->getNamespaceVersionKey();
        $version = Cache::get($key);
        if (!$version) {
            $version = 1;
            Cache::forever($key, $version);
        }
        return (int) $version;
    }

    /**
     * Get the cache key used to store the namespace version for this model.
     */
    protected function getNamespaceVersionKey(): string
    {
        $modelShort = $this->getModelShortName();
        return 'ns_version_' . strtolower($modelShort);
    }

    /**
     * Get the short class name of the model without namespace.
     */
    protected function getModelShortName(): string
    {
        $fqn = $this->getModelClass();
        return (new \ReflectionClass($fqn))->getShortName();
    }
}

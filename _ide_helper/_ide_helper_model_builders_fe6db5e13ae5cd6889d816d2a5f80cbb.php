<?php //9cf58dcf1330fdcbd965b64b80152c07
/** @noinspection all */

namespace LaravelIdea\Helper\Illuminate\Notifications {

    use Illuminate\Contracts\Database\Query\Expression;
    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Notifications\DatabaseNotificationCollection;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use LaravelIdea\Helper\_BaseBuilder;
    
    /**
     * @method DatabaseNotification create(array $attributes = [])
     * @method DatabaseNotification createOrFirst(array $attributes = [], array $values = [])
     * @method DatabaseNotification createOrRestore(array $attributes = [], array $values = [])
     * @method DatabaseNotification createQuietly(array $attributes = [])
     * @method DatabaseNotificationCollection|DatabaseNotification[] cursor()
     * @method DatabaseNotification[] eagerLoadRelations(array $models)
     * @method DatabaseNotification|null|DatabaseNotificationCollection|DatabaseNotification[] find($id, array|string $columns = ['*'])
     * @method DatabaseNotificationCollection|DatabaseNotification[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method DatabaseNotification|DatabaseNotificationCollection|DatabaseNotification[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method DatabaseNotification|DatabaseNotificationCollection|DatabaseNotification[] findOrFail($id, array|string $columns = ['*'])
     * @method DatabaseNotification|DatabaseNotificationCollection|DatabaseNotification[] findOrNew($id, array|string $columns = ['*'])
     * @method DatabaseNotification first(array|string $columns = ['*'])
     * @method DatabaseNotification firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method DatabaseNotification firstOrCreate(array $attributes = [], array $values = [])
     * @method DatabaseNotification firstOrFail(array|string $columns = ['*'])
     * @method DatabaseNotification firstOrNew(array $attributes = [], array $values = [])
     * @method DatabaseNotification firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DatabaseNotification forceCreate(array $attributes)
     * @method DatabaseNotification forceCreateQuietly(array $attributes = [])
     * @method DatabaseNotificationCollection|DatabaseNotification[] fromQuery(string $query, array $bindings = [])
     * @method DatabaseNotificationCollection|DatabaseNotification[] get(array|string $columns = ['*'])
     * @method DatabaseNotification getModel()
     * @method DatabaseNotification[] getModels(array|string $columns = ['*'])
     * @method DatabaseNotificationCollection|DatabaseNotification[] hydrate(array $items)
     * @method DatabaseNotification incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method DatabaseNotificationCollection|DatabaseNotification[] lazy(int $chunkSize = 1000)
     * @method DatabaseNotificationCollection|DatabaseNotification[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method DatabaseNotificationCollection|DatabaseNotification[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method DatabaseNotification make(array $attributes = [])
     * @method DatabaseNotification newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DatabaseNotification[]|DatabaseNotificationCollection paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method DatabaseNotification restoreOrCreate(array $attributes = [], array $values = [])
     * @method Paginator|DatabaseNotification[]|DatabaseNotificationCollection simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DatabaseNotification sole(array|string $columns = ['*'])
     * @method DatabaseNotification updateOrCreate(array $attributes, array $values = [])
     * @method _IH_DatabaseNotification_QB read()
     * @method _IH_DatabaseNotification_QB unread()
     */
    class _IH_DatabaseNotification_QB extends _BaseBuilder {}
}

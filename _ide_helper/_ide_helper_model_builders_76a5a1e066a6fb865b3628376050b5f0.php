<?php //7aa41f61c50298103a55e522c932d729
/** @noinspection all */

namespace LaravelIdea\Helper\App\Models {

    use App\Models\Comment;
    use App\Models\Content;
    use App\Models\Customer;
    use App\Models\DisabledDate;
    use App\Models\Image;
    use App\Models\Item;
    use App\Models\ItemGroup;
    use App\Models\Order;
    use App\Models\OrderItem;
    use App\Models\User;
    use Illuminate\Contracts\Database\Query\Expression;
    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use Illuminate\Support\Collection;
    use LaravelIdea\Helper\_BaseBuilder;
    use LaravelIdea\Helper\_BaseCollection;
    
    /**
     * @method Comment|null getOrPut($key, \Closure $value)
     * @method Comment|$this shift(int $count = 1)
     * @method Comment|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method Comment|$this pop(int $count = 1)
     * @method Comment|null pull($key, \Closure $default = null)
     * @method Comment|null last(callable|null $callback = null, \Closure $default = null)
     * @method Comment|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method Comment|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method Comment|null get($key, \Closure $default = null)
     * @method Comment|null first(callable|null $callback = null, \Closure $default = null)
     * @method Comment|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method Comment|null find($key, $default = null)
     * @method Comment[] all()
     */
    class _IH_Comment_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Comment[][]|Collection<_IH_Comment_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Comment_QB whereId($value)
     * @method _IH_Comment_QB whereUserId($value)
     * @method _IH_Comment_QB whereOrderId($value)
     * @method _IH_Comment_QB whereComment($value)
     * @method _IH_Comment_QB whereCreatedAt($value)
     * @method _IH_Comment_QB whereUpdatedAt($value)
     * @method Comment create(array $attributes = [])
     * @method Comment createOrFirst(array $attributes = [], array $values = [])
     * @method Comment createOrRestore($attributes = [], $values = [])
     * @method Comment createQuietly(array $attributes = [])
     * @method _IH_Comment_C|Comment[] cursor()
     * @method Comment[] eagerLoadRelations(array $models)
     * @method Comment|null|_IH_Comment_C|Comment[] find($id, array|string $columns = ['*'])
     * @method _IH_Comment_C|Comment[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method Comment|_IH_Comment_C|Comment[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Comment|_IH_Comment_C|Comment[] findOrFail($id, array|string $columns = ['*'])
     * @method Comment|_IH_Comment_C|Comment[] findOrNew($id, array|string $columns = ['*'])
     * @method Comment first(array|string $columns = ['*'])
     * @method Comment firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Comment firstOrCreate(array $attributes = [], array $values = [])
     * @method Comment firstOrFail(array|string $columns = ['*'])
     * @method Comment firstOrNew(array $attributes = [], array $values = [])
     * @method Comment firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Comment forceCreate(array $attributes)
     * @method Comment forceCreateQuietly(array $attributes = [])
     * @method _IH_Comment_C|Comment[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Comment_C|Comment[] get(array|string $columns = ['*'])
     * @method Comment getModel()
     * @method Comment[] getModels(array|string $columns = ['*'])
     * @method _IH_Comment_C|Comment[] hydrate(array $items)
     * @method Comment incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_Comment_C|Comment[] lazy(int $chunkSize = 1000)
     * @method _IH_Comment_C|Comment[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_Comment_C|Comment[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method Comment make(array $attributes = [])
     * @method Comment newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Comment[]|_IH_Comment_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method Comment restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|Comment[]|_IH_Comment_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Comment sole(array|string $columns = ['*'])
     * @method Comment updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Comment_QB extends _BaseBuilder {}
    
    /**
     * @method Content|null getOrPut($key, \Closure $value)
     * @method Content|$this shift(int $count = 1)
     * @method Content|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method Content|$this pop(int $count = 1)
     * @method Content|null pull($key, \Closure $default = null)
     * @method Content|null last(callable|null $callback = null, \Closure $default = null)
     * @method Content|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method Content|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method Content|null get($key, \Closure $default = null)
     * @method Content|null first(callable|null $callback = null, \Closure $default = null)
     * @method Content|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method Content|null find($key, $default = null)
     * @method Content[] all()
     */
    class _IH_Content_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Content[][]|Collection<_IH_Content_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Content_QB whereId($value)
     * @method _IH_Content_QB whereName($value)
     * @method _IH_Content_QB whereDescription($value)
     * @method _IH_Content_QB whereContent($value)
     * @method _IH_Content_QB whereCreatedAt($value)
     * @method _IH_Content_QB whereUpdatedAt($value)
     * @method Content create(array $attributes = [])
     * @method Content createOrFirst(array $attributes = [], array $values = [])
     * @method Content createOrRestore($attributes = [], $values = [])
     * @method Content createQuietly(array $attributes = [])
     * @method _IH_Content_C|Content[] cursor()
     * @method Content[] eagerLoadRelations(array $models)
     * @method Content|null|_IH_Content_C|Content[] find($id, array|string $columns = ['*'])
     * @method _IH_Content_C|Content[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method Content|_IH_Content_C|Content[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Content|_IH_Content_C|Content[] findOrFail($id, array|string $columns = ['*'])
     * @method Content|_IH_Content_C|Content[] findOrNew($id, array|string $columns = ['*'])
     * @method Content first(array|string $columns = ['*'])
     * @method Content firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Content firstOrCreate(array $attributes = [], array $values = [])
     * @method Content firstOrFail(array|string $columns = ['*'])
     * @method Content firstOrNew(array $attributes = [], array $values = [])
     * @method Content firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Content forceCreate(array $attributes)
     * @method Content forceCreateQuietly(array $attributes = [])
     * @method _IH_Content_C|Content[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Content_C|Content[] get(array|string $columns = ['*'])
     * @method Content getModel()
     * @method Content[] getModels(array|string $columns = ['*'])
     * @method _IH_Content_C|Content[] hydrate(array $items)
     * @method Content incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_Content_C|Content[] lazy(int $chunkSize = 1000)
     * @method _IH_Content_C|Content[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_Content_C|Content[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method Content make(array $attributes = [])
     * @method Content newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Content[]|_IH_Content_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method Content restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|Content[]|_IH_Content_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Content sole(array|string $columns = ['*'])
     * @method Content updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Content_QB extends _BaseBuilder {}
    
    /**
     * @method Customer|null getOrPut($key, \Closure $value)
     * @method Customer|$this shift(int $count = 1)
     * @method Customer|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method Customer|$this pop(int $count = 1)
     * @method Customer|null pull($key, \Closure $default = null)
     * @method Customer|null last(callable|null $callback = null, \Closure $default = null)
     * @method Customer|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method Customer|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method Customer|null get($key, \Closure $default = null)
     * @method Customer|null first(callable|null $callback = null, \Closure $default = null)
     * @method Customer|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method Customer|null find($key, $default = null)
     * @method Customer[] all()
     */
    class _IH_Customer_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Customer[][]|Collection<_IH_Customer_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Customer_QB whereId($value)
     * @method _IH_Customer_QB whereForename($value)
     * @method _IH_Customer_QB whereSurname($value)
     * @method _IH_Customer_QB whereLegalname($value)
     * @method _IH_Customer_QB whereStreet($value)
     * @method _IH_Customer_QB whereNumber($value)
     * @method _IH_Customer_QB whereZipcode($value)
     * @method _IH_Customer_QB whereCity($value)
     * @method _IH_Customer_QB whereEmail($value)
     * @method _IH_Customer_QB whereMobile($value)
     * @method _IH_Customer_QB whereCreatedAt($value)
     * @method _IH_Customer_QB whereUpdatedAt($value)
     * @method Customer create(array $attributes = [])
     * @method Customer createOrFirst(array $attributes = [], array $values = [])
     * @method Customer createOrRestore($attributes = [], $values = [])
     * @method Customer createQuietly(array $attributes = [])
     * @method _IH_Customer_C|Customer[] cursor()
     * @method Customer[] eagerLoadRelations(array $models)
     * @method Customer|null|_IH_Customer_C|Customer[] find($id, array|string $columns = ['*'])
     * @method _IH_Customer_C|Customer[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method Customer|_IH_Customer_C|Customer[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Customer|_IH_Customer_C|Customer[] findOrFail($id, array|string $columns = ['*'])
     * @method Customer|_IH_Customer_C|Customer[] findOrNew($id, array|string $columns = ['*'])
     * @method Customer first(array|string $columns = ['*'])
     * @method Customer firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Customer firstOrCreate(array $attributes = [], array $values = [])
     * @method Customer firstOrFail(array|string $columns = ['*'])
     * @method Customer firstOrNew(array $attributes = [], array $values = [])
     * @method Customer firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Customer forceCreate(array $attributes)
     * @method Customer forceCreateQuietly(array $attributes = [])
     * @method _IH_Customer_C|Customer[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Customer_C|Customer[] get(array|string $columns = ['*'])
     * @method Customer getModel()
     * @method Customer[] getModels(array|string $columns = ['*'])
     * @method _IH_Customer_C|Customer[] hydrate(array $items)
     * @method Customer incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_Customer_C|Customer[] lazy(int $chunkSize = 1000)
     * @method _IH_Customer_C|Customer[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_Customer_C|Customer[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method Customer make(array $attributes = [])
     * @method Customer newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Customer[]|_IH_Customer_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method Customer restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|Customer[]|_IH_Customer_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Customer sole(array|string $columns = ['*'])
     * @method Customer updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Customer_QB extends _BaseBuilder {}
    
    /**
     * @method DisabledDate|null getOrPut($key, \Closure $value)
     * @method DisabledDate|$this shift(int $count = 1)
     * @method DisabledDate|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method DisabledDate|$this pop(int $count = 1)
     * @method DisabledDate|null pull($key, \Closure $default = null)
     * @method DisabledDate|null last(callable|null $callback = null, \Closure $default = null)
     * @method DisabledDate|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method DisabledDate|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method DisabledDate|null get($key, \Closure $default = null)
     * @method DisabledDate|null first(callable|null $callback = null, \Closure $default = null)
     * @method DisabledDate|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method DisabledDate|null find($key, $default = null)
     * @method DisabledDate[] all()
     */
    class _IH_DisabledDate_C extends _BaseCollection {
        /**
         * @param int $size
         * @return DisabledDate[][]|Collection<_IH_DisabledDate_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_DisabledDate_QB whereId($value)
     * @method _IH_DisabledDate_QB whereStart($value)
     * @method _IH_DisabledDate_QB whereEnd($value)
     * @method _IH_DisabledDate_QB whereSiteNotice($value)
     * @method _IH_DisabledDate_QB whereComment($value)
     * @method _IH_DisabledDate_QB whereActive($value)
     * @method _IH_DisabledDate_QB whereCreatedAt($value)
     * @method _IH_DisabledDate_QB whereUpdatedAt($value)
     * @method DisabledDate create(array $attributes = [])
     * @method DisabledDate createOrFirst(array $attributes = [], array $values = [])
     * @method DisabledDate createOrRestore($attributes = [], $values = [])
     * @method DisabledDate createQuietly(array $attributes = [])
     * @method _IH_DisabledDate_C|DisabledDate[] cursor()
     * @method DisabledDate[] eagerLoadRelations(array $models)
     * @method DisabledDate|null|_IH_DisabledDate_C|DisabledDate[] find($id, array|string $columns = ['*'])
     * @method _IH_DisabledDate_C|DisabledDate[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method DisabledDate|_IH_DisabledDate_C|DisabledDate[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method DisabledDate|_IH_DisabledDate_C|DisabledDate[] findOrFail($id, array|string $columns = ['*'])
     * @method DisabledDate|_IH_DisabledDate_C|DisabledDate[] findOrNew($id, array|string $columns = ['*'])
     * @method DisabledDate first(array|string $columns = ['*'])
     * @method DisabledDate firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method DisabledDate firstOrCreate(array $attributes = [], array $values = [])
     * @method DisabledDate firstOrFail(array|string $columns = ['*'])
     * @method DisabledDate firstOrNew(array $attributes = [], array $values = [])
     * @method DisabledDate firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method DisabledDate forceCreate(array $attributes)
     * @method DisabledDate forceCreateQuietly(array $attributes = [])
     * @method _IH_DisabledDate_C|DisabledDate[] fromQuery(string $query, array $bindings = [])
     * @method _IH_DisabledDate_C|DisabledDate[] get(array|string $columns = ['*'])
     * @method DisabledDate getModel()
     * @method DisabledDate[] getModels(array|string $columns = ['*'])
     * @method _IH_DisabledDate_C|DisabledDate[] hydrate(array $items)
     * @method DisabledDate incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_DisabledDate_C|DisabledDate[] lazy(int $chunkSize = 1000)
     * @method _IH_DisabledDate_C|DisabledDate[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_DisabledDate_C|DisabledDate[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method DisabledDate make(array $attributes = [])
     * @method DisabledDate newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|DisabledDate[]|_IH_DisabledDate_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method DisabledDate restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|DisabledDate[]|_IH_DisabledDate_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method DisabledDate sole(array|string $columns = ['*'])
     * @method DisabledDate updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_DisabledDate_QB extends _BaseBuilder {}
    
    /**
     * @method Image|null getOrPut($key, \Closure $value)
     * @method Image|$this shift(int $count = 1)
     * @method Image|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method Image|$this pop(int $count = 1)
     * @method Image|null pull($key, \Closure $default = null)
     * @method Image|null last(callable|null $callback = null, \Closure $default = null)
     * @method Image|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method Image|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method Image|null get($key, \Closure $default = null)
     * @method Image|null first(callable|null $callback = null, \Closure $default = null)
     * @method Image|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method Image|null find($key, $default = null)
     * @method Image[] all()
     */
    class _IH_Image_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Image[][]|Collection<_IH_Image_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Image_QB whereId($value)
     * @method _IH_Image_QB whereItemId($value)
     * @method _IH_Image_QB wherePath($value)
     * @method _IH_Image_QB whereCreatedAt($value)
     * @method _IH_Image_QB whereUpdatedAt($value)
     * @method Image create(array $attributes = [])
     * @method Image createOrFirst(array $attributes = [], array $values = [])
     * @method Image createOrRestore($attributes = [], $values = [])
     * @method Image createQuietly(array $attributes = [])
     * @method _IH_Image_C|Image[] cursor()
     * @method Image[] eagerLoadRelations(array $models)
     * @method Image|null|_IH_Image_C|Image[] find($id, array|string $columns = ['*'])
     * @method _IH_Image_C|Image[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method Image|_IH_Image_C|Image[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Image|_IH_Image_C|Image[] findOrFail($id, array|string $columns = ['*'])
     * @method Image|_IH_Image_C|Image[] findOrNew($id, array|string $columns = ['*'])
     * @method Image first(array|string $columns = ['*'])
     * @method Image firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Image firstOrCreate(array $attributes = [], array $values = [])
     * @method Image firstOrFail(array|string $columns = ['*'])
     * @method Image firstOrNew(array $attributes = [], array $values = [])
     * @method Image firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Image forceCreate(array $attributes)
     * @method Image forceCreateQuietly(array $attributes = [])
     * @method _IH_Image_C|Image[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Image_C|Image[] get(array|string $columns = ['*'])
     * @method Image getModel()
     * @method Image[] getModels(array|string $columns = ['*'])
     * @method _IH_Image_C|Image[] hydrate(array $items)
     * @method Image incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_Image_C|Image[] lazy(int $chunkSize = 1000)
     * @method _IH_Image_C|Image[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_Image_C|Image[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method Image make(array $attributes = [])
     * @method Image newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Image[]|_IH_Image_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method Image restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|Image[]|_IH_Image_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Image sole(array|string $columns = ['*'])
     * @method Image updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Image_QB extends _BaseBuilder {}
    
    /**
     * @method ItemGroup|null getOrPut($key, \Closure $value)
     * @method ItemGroup|$this shift(int $count = 1)
     * @method ItemGroup|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method ItemGroup|$this pop(int $count = 1)
     * @method ItemGroup|null pull($key, \Closure $default = null)
     * @method ItemGroup|null last(callable|null $callback = null, \Closure $default = null)
     * @method ItemGroup|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method ItemGroup|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method ItemGroup|null get($key, \Closure $default = null)
     * @method ItemGroup|null first(callable|null $callback = null, \Closure $default = null)
     * @method ItemGroup|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method ItemGroup|null find($key, $default = null)
     * @method ItemGroup[] all()
     */
    class _IH_ItemGroup_C extends _BaseCollection {
        /**
         * @param int $size
         * @return ItemGroup[][]|Collection<_IH_ItemGroup_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_ItemGroup_QB whereId($value)
     * @method _IH_ItemGroup_QB whereName($value)
     * @method _IH_ItemGroup_QB whereDescription($value)
     * @method _IH_ItemGroup_QB whereCreatedAt($value)
     * @method _IH_ItemGroup_QB whereUpdatedAt($value)
     * @method _IH_ItemGroup_QB whereImageId($value)
     * @method ItemGroup create(array $attributes = [])
     * @method ItemGroup createOrFirst(array $attributes = [], array $values = [])
     * @method ItemGroup createOrRestore($attributes = [], $values = [])
     * @method ItemGroup createQuietly(array $attributes = [])
     * @method _IH_ItemGroup_C|ItemGroup[] cursor()
     * @method ItemGroup[] eagerLoadRelations(array $models)
     * @method ItemGroup|null|_IH_ItemGroup_C|ItemGroup[] find($id, array|string $columns = ['*'])
     * @method _IH_ItemGroup_C|ItemGroup[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method ItemGroup|_IH_ItemGroup_C|ItemGroup[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method ItemGroup|_IH_ItemGroup_C|ItemGroup[] findOrFail($id, array|string $columns = ['*'])
     * @method ItemGroup|_IH_ItemGroup_C|ItemGroup[] findOrNew($id, array|string $columns = ['*'])
     * @method ItemGroup first(array|string $columns = ['*'])
     * @method ItemGroup firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method ItemGroup firstOrCreate(array $attributes = [], array $values = [])
     * @method ItemGroup firstOrFail(array|string $columns = ['*'])
     * @method ItemGroup firstOrNew(array $attributes = [], array $values = [])
     * @method ItemGroup firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method ItemGroup forceCreate(array $attributes)
     * @method ItemGroup forceCreateQuietly(array $attributes = [])
     * @method _IH_ItemGroup_C|ItemGroup[] fromQuery(string $query, array $bindings = [])
     * @method _IH_ItemGroup_C|ItemGroup[] get(array|string $columns = ['*'])
     * @method ItemGroup getModel()
     * @method ItemGroup[] getModels(array|string $columns = ['*'])
     * @method _IH_ItemGroup_C|ItemGroup[] hydrate(array $items)
     * @method ItemGroup incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_ItemGroup_C|ItemGroup[] lazy(int $chunkSize = 1000)
     * @method _IH_ItemGroup_C|ItemGroup[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_ItemGroup_C|ItemGroup[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method ItemGroup make(array $attributes = [])
     * @method ItemGroup newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|ItemGroup[]|_IH_ItemGroup_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method ItemGroup restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|ItemGroup[]|_IH_ItemGroup_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method ItemGroup sole(array|string $columns = ['*'])
     * @method ItemGroup updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_ItemGroup_QB extends _BaseBuilder {}
    
    /**
     * @method Item|null getOrPut($key, \Closure $value)
     * @method Item|$this shift(int $count = 1)
     * @method Item|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method Item|$this pop(int $count = 1)
     * @method Item|null pull($key, \Closure $default = null)
     * @method Item|null last(callable|null $callback = null, \Closure $default = null)
     * @method Item|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method Item|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method Item|null get($key, \Closure $default = null)
     * @method Item|null first(callable|null $callback = null, \Closure $default = null)
     * @method Item|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method Item|null find($key, $default = null)
     * @method Item[] all()
     */
    class _IH_Item_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Item[][]|Collection<_IH_Item_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Item_QB whereId($value)
     * @method _IH_Item_QB whereName($value)
     * @method _IH_Item_QB whereDescription($value)
     * @method _IH_Item_QB whereAmount($value)
     * @method _IH_Item_QB whereAvailable($value)
     * @method _IH_Item_QB whereVisible($value)
     * @method _IH_Item_QB wherePrice($value)
     * @method _IH_Item_QB whereDeposit($value)
     * @method _IH_Item_QB whereItemGroupId($value)
     * @method _IH_Item_QB whereCreatedAt($value)
     * @method _IH_Item_QB whereUpdatedAt($value)
     * @method Item create(array $attributes = [])
     * @method Item createOrFirst(array $attributes = [], array $values = [])
     * @method Item createOrRestore($attributes = [], $values = [])
     * @method Item createQuietly(array $attributes = [])
     * @method _IH_Item_C|Item[] cursor()
     * @method Item[] eagerLoadRelations(array $models)
     * @method Item|null|_IH_Item_C|Item[] find($id, array|string $columns = ['*'])
     * @method _IH_Item_C|Item[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method Item|_IH_Item_C|Item[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Item|_IH_Item_C|Item[] findOrFail($id, array|string $columns = ['*'])
     * @method Item|_IH_Item_C|Item[] findOrNew($id, array|string $columns = ['*'])
     * @method Item first(array|string $columns = ['*'])
     * @method Item firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Item firstOrCreate(array $attributes = [], array $values = [])
     * @method Item firstOrFail(array|string $columns = ['*'])
     * @method Item firstOrNew(array $attributes = [], array $values = [])
     * @method Item firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Item forceCreate(array $attributes)
     * @method Item forceCreateQuietly(array $attributes = [])
     * @method _IH_Item_C|Item[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Item_C|Item[] get(array|string $columns = ['*'])
     * @method Item getModel()
     * @method Item[] getModels(array|string $columns = ['*'])
     * @method _IH_Item_C|Item[] hydrate(array $items)
     * @method Item incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_Item_C|Item[] lazy(int $chunkSize = 1000)
     * @method _IH_Item_C|Item[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_Item_C|Item[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method Item make(array $attributes = [])
     * @method Item newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Item[]|_IH_Item_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method Item restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|Item[]|_IH_Item_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Item sole(array|string $columns = ['*'])
     * @method Item updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Item_QB extends _BaseBuilder {}
    
    /**
     * @method OrderItem|null getOrPut($key, \Closure $value)
     * @method OrderItem|$this shift(int $count = 1)
     * @method OrderItem|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method OrderItem|$this pop(int $count = 1)
     * @method OrderItem|null pull($key, \Closure $default = null)
     * @method OrderItem|null last(callable|null $callback = null, \Closure $default = null)
     * @method OrderItem|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method OrderItem|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method OrderItem|null get($key, \Closure $default = null)
     * @method OrderItem|null first(callable|null $callback = null, \Closure $default = null)
     * @method OrderItem|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method OrderItem|null find($key, $default = null)
     * @method OrderItem[] all()
     */
    class _IH_OrderItem_C extends _BaseCollection {
        /**
         * @param int $size
         * @return OrderItem[][]|Collection<_IH_OrderItem_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_OrderItem_QB whereId($value)
     * @method _IH_OrderItem_QB whereOrderId($value)
     * @method _IH_OrderItem_QB whereItemId($value)
     * @method _IH_OrderItem_QB whereQuantity($value)
     * @method _IH_OrderItem_QB whereStart($value)
     * @method _IH_OrderItem_QB whereEnd($value)
     * @method _IH_OrderItem_QB whereOriginalPrice($value)
     * @method _IH_OrderItem_QB wherePrice($value)
     * @method _IH_OrderItem_QB whereComment($value)
     * @method _IH_OrderItem_QB whereCreatedAt($value)
     * @method _IH_OrderItem_QB whereUpdatedAt($value)
     * @method OrderItem create(array $attributes = [])
     * @method OrderItem createOrFirst(array $attributes = [], array $values = [])
     * @method OrderItem createOrRestore($attributes = [], $values = [])
     * @method OrderItem createQuietly(array $attributes = [])
     * @method _IH_OrderItem_C|OrderItem[] cursor()
     * @method OrderItem[] eagerLoadRelations(array $models)
     * @method OrderItem|null|_IH_OrderItem_C|OrderItem[] find($id, array|string $columns = ['*'])
     * @method _IH_OrderItem_C|OrderItem[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method OrderItem|_IH_OrderItem_C|OrderItem[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method OrderItem|_IH_OrderItem_C|OrderItem[] findOrFail($id, array|string $columns = ['*'])
     * @method OrderItem|_IH_OrderItem_C|OrderItem[] findOrNew($id, array|string $columns = ['*'])
     * @method OrderItem first(array|string $columns = ['*'])
     * @method OrderItem firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method OrderItem firstOrCreate(array $attributes = [], array $values = [])
     * @method OrderItem firstOrFail(array|string $columns = ['*'])
     * @method OrderItem firstOrNew(array $attributes = [], array $values = [])
     * @method OrderItem firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method OrderItem forceCreate(array $attributes)
     * @method OrderItem forceCreateQuietly(array $attributes = [])
     * @method _IH_OrderItem_C|OrderItem[] fromQuery(string $query, array $bindings = [])
     * @method _IH_OrderItem_C|OrderItem[] get(array|string $columns = ['*'])
     * @method OrderItem getModel()
     * @method OrderItem[] getModels(array|string $columns = ['*'])
     * @method _IH_OrderItem_C|OrderItem[] hydrate(array $items)
     * @method OrderItem incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_OrderItem_C|OrderItem[] lazy(int $chunkSize = 1000)
     * @method _IH_OrderItem_C|OrderItem[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_OrderItem_C|OrderItem[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method OrderItem make(array $attributes = [])
     * @method OrderItem newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|OrderItem[]|_IH_OrderItem_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method OrderItem restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|OrderItem[]|_IH_OrderItem_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method OrderItem sole(array|string $columns = ['*'])
     * @method OrderItem updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_OrderItem_QB extends _BaseBuilder {}
    
    /**
     * @method Order|null getOrPut($key, \Closure $value)
     * @method Order|$this shift(int $count = 1)
     * @method Order|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method Order|$this pop(int $count = 1)
     * @method Order|null pull($key, \Closure $default = null)
     * @method Order|null last(callable|null $callback = null, \Closure $default = null)
     * @method Order|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method Order|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method Order|null get($key, \Closure $default = null)
     * @method Order|null first(callable|null $callback = null, \Closure $default = null)
     * @method Order|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method Order|null find($key, $default = null)
     * @method Order[] all()
     */
    class _IH_Order_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Order[][]|Collection<_IH_Order_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_Order_QB whereId($value)
     * @method _IH_Order_QB whereStatus($value)
     * @method _IH_Order_QB whereRate($value)
     * @method _IH_Order_QB whereEventName($value)
     * @method _IH_Order_QB whereNote($value)
     * @method _IH_Order_QB whereCustomerId($value)
     * @method _IH_Order_QB whereDeposit($value)
     * @method _IH_Order_QB whereCreatedAt($value)
     * @method _IH_Order_QB whereUpdatedAt($value)
     * @method Order create(array $attributes = [])
     * @method Order createOrFirst(array $attributes = [], array $values = [])
     * @method Order createOrRestore($attributes = [], $values = [])
     * @method Order createQuietly(array $attributes = [])
     * @method _IH_Order_C|Order[] cursor()
     * @method Order[] eagerLoadRelations(array $models)
     * @method Order|null|_IH_Order_C|Order[] find($id, array|string $columns = ['*'])
     * @method _IH_Order_C|Order[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method Order|_IH_Order_C|Order[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Order|_IH_Order_C|Order[] findOrFail($id, array|string $columns = ['*'])
     * @method Order|_IH_Order_C|Order[] findOrNew($id, array|string $columns = ['*'])
     * @method Order first(array|string $columns = ['*'])
     * @method Order firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method Order firstOrCreate(array $attributes = [], array $values = [])
     * @method Order firstOrFail(array|string $columns = ['*'])
     * @method Order firstOrNew(array $attributes = [], array $values = [])
     * @method Order firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Order forceCreate(array $attributes)
     * @method Order forceCreateQuietly(array $attributes = [])
     * @method _IH_Order_C|Order[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Order_C|Order[] get(array|string $columns = ['*'])
     * @method Order getModel()
     * @method Order[] getModels(array|string $columns = ['*'])
     * @method _IH_Order_C|Order[] hydrate(array $items)
     * @method Order incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_Order_C|Order[] lazy(int $chunkSize = 1000)
     * @method _IH_Order_C|Order[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_Order_C|Order[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method Order make(array $attributes = [])
     * @method Order newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Order[]|_IH_Order_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method Order restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|Order[]|_IH_Order_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Order sole(array|string $columns = ['*'])
     * @method Order updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Order_QB extends _BaseBuilder {}
    
    /**
     * @method User|null getOrPut($key, \Closure $value)
     * @method User|$this shift(int $count = 1)
     * @method User|null firstOrFail(callable|string $key = null, $operator = null, $value = null)
     * @method User|$this pop(int $count = 1)
     * @method User|null pull($key, \Closure $default = null)
     * @method User|null last(callable|null $callback = null, \Closure $default = null)
     * @method User|$this random(callable|int|null $number = null, bool $preserveKeys = false)
     * @method User|null sole(callable|string $key = null, $operator = null, $value = null)
     * @method User|null get($key, \Closure $default = null)
     * @method User|null first(callable|null $callback = null, \Closure $default = null)
     * @method User|null firstWhere(callable|string $key, $operator = null, $value = null)
     * @method User|null find($key, $default = null)
     * @method User[] all()
     */
    class _IH_User_C extends _BaseCollection {
        /**
         * @param int $size
         * @return User[][]|Collection<_IH_User_C>
         */
        public function chunk($size)
        {
            return [];
        }
    }
    
    /**
     * @method _IH_User_QB whereId($value)
     * @method _IH_User_QB whereUsername($value)
     * @method _IH_User_QB whereForename($value)
     * @method _IH_User_QB whereSurname($value)
     * @method _IH_User_QB whereEmail($value)
     * @method _IH_User_QB wherePassword($value)
     * @method _IH_User_QB whereRole($value)
     * @method _IH_User_QB whereEnabled($value)
     * @method _IH_User_QB whereLastLogin($value)
     * @method _IH_User_QB whereRememberToken($value)
     * @method _IH_User_QB whereCreatedAt($value)
     * @method _IH_User_QB whereUpdatedAt($value)
     * @method User create(array $attributes = [])
     * @method User createOrFirst(array $attributes = [], array $values = [])
     * @method User createOrRestore($attributes = [], $values = [])
     * @method User createQuietly(array $attributes = [])
     * @method _IH_User_C|User[] cursor()
     * @method User[] eagerLoadRelations(array $models)
     * @method User|null|_IH_User_C|User[] find($id, array|string $columns = ['*'])
     * @method _IH_User_C|User[] findMany(array|Arrayable $ids, array|string $columns = ['*'])
     * @method User|_IH_User_C|User[] findOr($id, \Closure|string|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method User|_IH_User_C|User[] findOrFail($id, array|string $columns = ['*'])
     * @method User|_IH_User_C|User[] findOrNew($id, array|string $columns = ['*'])
     * @method User first(array|string $columns = ['*'])
     * @method User firstOr(\Closure|string[] $columns = ['*'], \Closure|null $callback = null)
     * @method User firstOrCreate(array $attributes = [], array $values = [])
     * @method User firstOrFail(array|string $columns = ['*'])
     * @method User firstOrNew(array $attributes = [], array $values = [])
     * @method User firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method User forceCreate(array $attributes)
     * @method User forceCreateQuietly(array $attributes = [])
     * @method _IH_User_C|User[] fromQuery(string $query, array $bindings = [])
     * @method _IH_User_C|User[] get(array|string $columns = ['*'])
     * @method User getModel()
     * @method User[] getModels(array|string $columns = ['*'])
     * @method _IH_User_C|User[] hydrate(array $items)
     * @method User incrementOrCreate(array $attributes, string $column = 'count', float|int $default = 1, float|int $step = 1, array $extra = [])
     * @method _IH_User_C|User[] lazy(int $chunkSize = 1000)
     * @method _IH_User_C|User[] lazyById(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method _IH_User_C|User[] lazyByIdDesc(int $chunkSize = 1000, null|string $column = null, null|string $alias = null)
     * @method User make(array $attributes = [])
     * @method User newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|User[]|_IH_User_C paginate(\Closure|int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null, \Closure|int|null $total = null)
     * @method User restoreOrCreate($attributes = [], $values = [])
     * @method Paginator|User[]|_IH_User_C simplePaginate(int|null $perPage = null, array|string $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method User sole(array|string $columns = ['*'])
     * @method User updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_User_QB extends _BaseBuilder {}
}
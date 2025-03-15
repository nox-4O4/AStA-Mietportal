<?php //5485b2e6c4080bcc95beed3cae75e43c
/** @noinspection all */

namespace App\Models {

    use App\Enums\OrderStatus;
    use App\Enums\UserRole;
    use Carbon\CarbonImmutable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasManyThrough;
    use Illuminate\Database\Eloquent\Relations\MorphToMany;
    use Illuminate\Notifications\DatabaseNotification;
    use Illuminate\Notifications\DatabaseNotificationCollection;
    use LaravelIdea\Helper\App\Models\_IH_Comment_C;
    use LaravelIdea\Helper\App\Models\_IH_Comment_QB;
    use LaravelIdea\Helper\App\Models\_IH_Content_C;
    use LaravelIdea\Helper\App\Models\_IH_Content_QB;
    use LaravelIdea\Helper\App\Models\_IH_Customer_C;
    use LaravelIdea\Helper\App\Models\_IH_Customer_QB;
    use LaravelIdea\Helper\App\Models\_IH_DisabledDate_C;
    use LaravelIdea\Helper\App\Models\_IH_DisabledDate_QB;
    use LaravelIdea\Helper\App\Models\_IH_Image_C;
    use LaravelIdea\Helper\App\Models\_IH_Image_QB;
    use LaravelIdea\Helper\App\Models\_IH_ItemGroup_C;
    use LaravelIdea\Helper\App\Models\_IH_ItemGroup_QB;
    use LaravelIdea\Helper\App\Models\_IH_Item_C;
    use LaravelIdea\Helper\App\Models\_IH_Item_QB;
    use LaravelIdea\Helper\App\Models\_IH_OrderItem_C;
    use LaravelIdea\Helper\App\Models\_IH_OrderItem_QB;
    use LaravelIdea\Helper\App\Models\_IH_Order_C;
    use LaravelIdea\Helper\App\Models\_IH_Order_QB;
    use LaravelIdea\Helper\App\Models\_IH_User_C;
    use LaravelIdea\Helper\App\Models\_IH_User_QB;
    use LaravelIdea\Helper\Illuminate\Notifications\_IH_DatabaseNotification_QB;
    
    /**
     * @property int $id
     * @property int|null $user_id
     * @property int $order_id
     * @property string $comment
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property Order $order
     * @method BelongsTo|_IH_Order_QB order()
     * @property User|null $user
     * @method BelongsTo|_IH_User_QB user()
     * @method static _IH_Comment_QB onWriteConnection()
     * @method _IH_Comment_QB newQuery()
     * @method static _IH_Comment_QB on(null|string $connection = null)
     * @method static _IH_Comment_QB query()
     * @method static _IH_Comment_QB with(array|string $relations)
     * @method _IH_Comment_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Comment_C|Comment[] all()
     * @ownLinks user_id,\App\Models\User,id|order_id,\App\Models\Order,id
     * @mixin _IH_Comment_QB
     */
    class Comment extends Model {}
    
    /**
     * @property int $id
     * @property string $name
     * @property string $description
     * @property $content
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @method static _IH_Content_QB onWriteConnection()
     * @method _IH_Content_QB newQuery()
     * @method static _IH_Content_QB on(null|string $connection = null)
     * @method static _IH_Content_QB query()
     * @method static _IH_Content_QB with(array|string $relations)
     * @method _IH_Content_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Content_C|Content[] all()
     * @mixin _IH_Content_QB
     */
    class Content extends Model {}
    
    /**
     * @property int $id
     * @property string $forename
     * @property string $surname
     * @property string|null $legalname
     * @property string|null $street
     * @property string|null $number
     * @property string|null $zipcode
     * @property string|null $city
     * @property string $email
     * @property string $mobile
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property-read string $name attribute
     * @property _IH_Order_C|Order[] $orders
     * @property-read int $orders_count
     * @method HasMany|_IH_Order_QB orders()
     * @method static _IH_Customer_QB onWriteConnection()
     * @method _IH_Customer_QB newQuery()
     * @method static _IH_Customer_QB on(null|string $connection = null)
     * @method static _IH_Customer_QB query()
     * @method static _IH_Customer_QB with(array|string $relations)
     * @method _IH_Customer_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Customer_C|Customer[] all()
     * @foreignLinks id,\App\Models\Order,customer_id
     * @mixin _IH_Customer_QB
     */
    class Customer extends Model {}
    
    /**
     * @property int $id
     * @property CarbonImmutable $start
     * @property CarbonImmutable $end
     * @property string $site_notice
     * @property string $comment
     * @property bool $active
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @method static _IH_DisabledDate_QB onWriteConnection()
     * @method _IH_DisabledDate_QB newQuery()
     * @method static _IH_DisabledDate_QB on(null|string $connection = null)
     * @method static _IH_DisabledDate_QB query()
     * @method static _IH_DisabledDate_QB with(array|string $relations)
     * @method _IH_DisabledDate_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_DisabledDate_C|DisabledDate[] all()
     * @mixin _IH_DisabledDate_QB
     */
    class DisabledDate extends Model {}
    
    /**
     * @property int $id
     * @property int $item_id
     * @property string $path
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property Item $item
     * @method BelongsTo|_IH_Item_QB item()
     * @method static _IH_Image_QB onWriteConnection()
     * @method _IH_Image_QB newQuery()
     * @method static _IH_Image_QB on(null|string $connection = null)
     * @method static _IH_Image_QB query()
     * @method static _IH_Image_QB with(array|string $relations)
     * @method _IH_Image_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Image_C|Image[] all()
     * @ownLinks item_id,\App\Models\Item,id
     * @foreignLinks id,\App\Models\ItemGroup,image_id
     * @mixin _IH_Image_QB
     */
    class Image extends Model {}
    
    /**
     * @property int $id
     * @property string $name
     * @property $description
     * @property int $amount
     * @property bool $available
     * @property bool $visible
     * @property float $price
     * @property float $deposit
     * @property int|null $item_group_id
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property-read mixed $raw_name attribute
     * @property-read string $slug attribute
     * @property _IH_Image_C|Image[] $images
     * @property-read int $images_count
     * @method HasMany|_IH_Image_QB images()
     * @property ItemGroup|null $itemGroup
     * @method BelongsTo|_IH_ItemGroup_QB itemGroup()
     * @property _IH_OrderItem_C|OrderItem[] $orderItems
     * @property-read int $order_items_count
     * @method HasMany|_IH_OrderItem_QB orderItems()
     * @property _IH_Order_C|Order[] $orders
     * @property-read int $orders_count
     * @method BelongsToMany|_IH_Order_QB orders()
     * @method static _IH_Item_QB onWriteConnection()
     * @method _IH_Item_QB newQuery()
     * @method static _IH_Item_QB on(null|string $connection = null)
     * @method static _IH_Item_QB query()
     * @method static _IH_Item_QB with(array|string $relations)
     * @method _IH_Item_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Item_C|Item[] all()
     * @ownLinks item_group_id,\App\Models\ItemGroup,id
     * @foreignLinks id,\App\Models\Image,item_id|id,\App\Models\OrderItem,item_id
     * @mixin _IH_Item_QB
     */
    class Item extends Model {}
    
    /**
     * @property int $id
     * @property string $name
     * @property null $description
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property int|null $image_id
     * @property-read string $slug attribute
     * @property Image|null $image
     * @method BelongsTo|_IH_Image_QB image()
     * @property _IH_Image_C|Image[] $itemImages
     * @property-read int $item_images_count
     * @method HasManyThrough|_IH_Image_QB itemImages()
     * @property _IH_Item_C|Item[] $items
     * @property-read int $items_count
     * @method HasMany|_IH_Item_QB items()
     * @method static _IH_ItemGroup_QB onWriteConnection()
     * @method _IH_ItemGroup_QB newQuery()
     * @method static _IH_ItemGroup_QB on(null|string $connection = null)
     * @method static _IH_ItemGroup_QB query()
     * @method static _IH_ItemGroup_QB with(array|string $relations)
     * @method _IH_ItemGroup_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_ItemGroup_C|ItemGroup[] all()
     * @ownLinks image_id,\App\Models\Image,id
     * @foreignLinks id,\App\Models\Item,item_group_id
     * @mixin _IH_ItemGroup_QB
     */
    class ItemGroup extends Model {}
    
    /**
     * @property int $id
     * @property OrderStatus $status
     * @property float $rate
     * @property string $event_name
     * @property string $note
     * @property int $customer_id
     * @property float $deposit
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property-read CarbonImmutable|null $common_end attribute
     * @property-read CarbonImmutable|null $common_start attribute
     * @property-read CarbonImmutable|null $first_start attribute
     * @property-read CarbonImmutable|null $last_end attribute
     * @property-read float $total attribute
     * @property-read float $total_discount attribute
     * @property _IH_Comment_C|Comment[] $comments
     * @property-read int $comments_count
     * @method HasMany|_IH_Comment_QB comments()
     * @property Customer $customer
     * @method BelongsTo|_IH_Customer_QB customer()
     * @property _IH_OrderItem_C|OrderItem[] $firstStart
     * @property-read int $first_start_count
     * @method HasMany|_IH_OrderItem_QB firstStart()
     * @property _IH_OrderItem_C|OrderItem[] $hasSinglePeriod
     * @property-read int $has_single_period_count
     * @method HasMany|_IH_OrderItem_QB hasSinglePeriod()
     * @property _IH_Item_C|Item[] $items
     * @property-read int $items_count
     * @method BelongsToMany|_IH_Item_QB items()
     * @property _IH_OrderItem_C|OrderItem[] $lastEnd
     * @property-read int $last_end_count
     * @method HasMany|_IH_OrderItem_QB lastEnd()
     * @property _IH_OrderItem_C|OrderItem[] $orderItems
     * @property-read int $order_items_count
     * @method HasMany|_IH_OrderItem_QB orderItems()
     * @property-read int $total_count
     * @method HasMany|_IH_OrderItem_QB total()
     * @method static _IH_Order_QB onWriteConnection()
     * @method _IH_Order_QB newQuery()
     * @method static _IH_Order_QB on(null|string $connection = null)
     * @method static _IH_Order_QB query()
     * @method static _IH_Order_QB with(array|string $relations)
     * @method _IH_Order_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_Order_C|Order[] all()
     * @ownLinks customer_id,\App\Models\Customer,id
     * @foreignLinks id,\App\Models\OrderItem,order_id|id,\App\Models\Comment,order_id
     * @mixin _IH_Order_QB
     */
    class Order extends Model {}
    
    /**
     * @property int $id
     * @property int $order_id
     * @property int $item_id
     * @property int $quantity
     * @property CarbonImmutable $start
     * @property CarbonImmutable $end
     * @property float $original_price
     * @property float $price
     * @property string $comment
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property Item $item
     * @method BelongsTo|_IH_Item_QB item()
     * @property Order $order
     * @method BelongsTo|_IH_Order_QB order()
     * @method static _IH_OrderItem_QB onWriteConnection()
     * @method _IH_OrderItem_QB newQuery()
     * @method static _IH_OrderItem_QB on(null|string $connection = null)
     * @method static _IH_OrderItem_QB query()
     * @method static _IH_OrderItem_QB with(array|string $relations)
     * @method _IH_OrderItem_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_OrderItem_C|OrderItem[] all()
     * @ownLinks order_id,\App\Models\Order,id|item_id,\App\Models\Item,id
     * @mixin _IH_OrderItem_QB
     */
    class OrderItem extends Model {}
    
    /**
     * @property int $id
     * @property string $username
     * @property string $forename
     * @property string $surname
     * @property string $email
     * @property mixed|null $password
     * @property UserRole $role
     * @property bool $enabled
     * @property CarbonImmutable|null $last_login
     * @property string|null $remember_token
     * @property CarbonImmutable|null $created_at
     * @property CarbonImmutable|null $updated_at
     * @property-read string $name attribute
     * @property _IH_Comment_C|Comment[] $comments
     * @property-read int $comments_count
     * @method HasMany|_IH_Comment_QB comments()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
     * @property-read int $notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB notifications()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $readNotifications
     * @property-read int $read_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB readNotifications()
     * @property DatabaseNotificationCollection|DatabaseNotification[] $unreadNotifications
     * @property-read int $unread_notifications_count
     * @method MorphToMany|_IH_DatabaseNotification_QB unreadNotifications()
     * @method static _IH_User_QB onWriteConnection()
     * @method _IH_User_QB newQuery()
     * @method static _IH_User_QB on(null|string $connection = null)
     * @method static _IH_User_QB query()
     * @method static _IH_User_QB with(array|string $relations)
     * @method _IH_User_QB newModelQuery()
     * @method false|int increment(string $column, float|int $amount = 1, array $extra = [])
     * @method false|int decrement(string $column, float|int $amount = 1, array $extra = [])
     * @method static _IH_User_C|User[] all()
     * @foreignLinks id,\App\Models\Comment,user_id
     * @mixin _IH_User_QB
     */
    class User extends Model {}
}
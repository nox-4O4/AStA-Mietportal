<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Str;

	/**
	 * @property int        $id
	 * @property string     $name
	 * @property ?int       $item_group_id
	 * @property ?ItemGroup $itemGroup
	 * @property string     $description
	 * @property int        $amount
	 * @property bool       $available
	 * @property bool       $visible
	 * @property float      $price
	 * @property float      $deposit
	 * @property ?DateTime  $created_at
	 * @property ?DateTime  $updated_at
	 */
	class Item extends Model {
		protected $fillable = [
			'name',
			'description',
			'amount',
			'available',
			'visible',
			'price',
			'deposit',
		];

		protected static function booted(): void {
			static::updating(function (Item $item) {
				$oldGroupId   = $item->getOriginal('item_group_id');
				$currentGroup = $item->itemGroup;

				if($oldGroupId !== $currentGroup?->id) {
					// check if old group image has to be removed
					$oldGroup = ItemGroup::find($oldGroupId);
					if($oldGroup && $oldGroup->image?->item->id == $item->id) {
						if($newImage = $oldGroup->itemImages()->first())
							$oldGroup->image()->associate($newImage)->save();
						else
							$oldGroup->image()->dissociate()->save();
					}

					// check if new group image should be set
					if($currentGroup && !$currentGroup->image && $item->images->isNotEmpty())
						$currentGroup->image()->associate($item->images->first())
						             ->save();
				}
			});
			static::deleting(function (Item $item) {
				// delete group when its last item gets deleted
				if($item->itemGroup?->items()->whereNot('id', $item->id)->count() === 0)
					$item->itemGroup->delete();

				$item->images->each->delete();
			});
		}

		public function getSearchFilterValues(bool $includeDescription): array {
			return $includeDescription
				? [$this->name, $this->description]
				: [$this->name];
		}

		protected function name(): Attribute {
			return Attribute::make(fn(string $value) => $this->itemGroup ? "{$this->itemGroup->name} - $value" : $value);
		}

		protected function slug(): Attribute {
			return Attribute::get(fn() => Str::slug($this->name, language: 'de'));
		}

		public function rawName(): string {
			return $this->attributes['name'];
		}

		public function itemGroup(): BelongsTo {
			return $this->belongsTo(ItemGroup::class);
		}

		public function images(): HasMany {
			return $this->hasMany(Image::class)->orderBy('id')->chaperone();
		}

		public function orderItems(): HasMany {
			return $this->hasMany(OrderItem::class)->orderBy('id')->chaperone();
		}

		public function orders(): BelongsToMany {
			return $this->belongsToMany(Order::class)->using(OrderItem::class);
		}

		/**
		 * Gets all elements that should be displayed in shop item list. Grouped items will be returned as a single element, ungrouped items will be returned unchanged.
		 * Result is ordered by the amount of non-cancelled orders that contain the corresponding ungrouped item or an item of the corresponding group.
		 *
		 * @return array
		 */
		public static function getDisplayItemElements(): array {
			return DB::select(<<<SQL
				WITH singleItems AS (SELECT DISTINCT items.id,
				                                     name,
				                                     FIRST_VALUE(images.path) OVER (PARTITION BY item_id ORDER BY images.id) imagePath,
				                                     0                                                                       grouped,
				                                     (SELECT COUNT(DISTINCT orders.id)
				                                      FROM orders
				                                      JOIN order_item ON orders.id = order_item.order_id
				                                      WHERE orders.status != 'cancelled' AND
				                                            order_item.item_id = items.id)                                   orders,
				                                     visible
				                     FROM items
				                     LEFT JOIN images ON items.id = images.item_id
				                     WHERE item_group_id IS NULL),
				     groupedItems AS (SELECT item_groups.id,
				                             item_groups.name,
				                             images.path                                       imagePath,
				                             1                                                 grouped,
				                             (SELECT COUNT(DISTINCT orders.id)
				                              FROM orders
				                              JOIN order_item ON orders.id = order_item.order_id
				                              JOIN items innerItems ON order_item.item_id = innerItems.id
				                              WHERE orders.status != 'cancelled' AND
				                                    innerItems.item_group_id = item_groups.id) orders,
				                             SUM(visible) > 0
				                      FROM item_groups
				                      JOIN items ON item_groups.id = items.item_group_id
				                      LEFT JOIN images ON item_groups.image_id = images.id
				                      GROUP BY id)

				SELECT * FROM singleItems
				UNION ALL
				SELECT * FROM groupedItems

				ORDER BY orders DESC, name
				SQL
			);
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'available' => 'bool',
				'visible'   => 'bool',
				'price'     => 'float',
				'deposit'   => 'float',
				'amount'    => 'int',
			];
		}
	}

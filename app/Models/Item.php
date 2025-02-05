<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;
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
			static::deleting(function (Item $item) {
				// delete group when its last item gets deleted
				if($item->itemGroup?->items()->whereNot('id', $item->id)->count() === 0)
					$item->itemGroup->delete();

				$item->images->each->delete();
			});
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

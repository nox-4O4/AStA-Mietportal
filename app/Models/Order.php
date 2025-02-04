<?php

	namespace App\Models;

	use App\Enums\OrderStatus;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Support\Collection;

	/**
	 * @property OrderStatus           $status
	 * @property float                 $rate
	 * @property string                $event_name
	 * @property string                $note
	 * @property Customer              $customer
	 * @property float                 $deposit
	 * @property Collection<OrderItem> $orderItems
	 * @property Collection<Item>      $items
	 */
	class Order extends Model {

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'status',
			'rate',
			'event_name',
			'note',
			'deposit',
		];

		public function customer(): BelongsTo {
			return $this->belongsTo(Customer::class);
		}

		public function orderItems(): HasMany {
			return $this->hasMany(OrderItem::class)->orderBy('id')->chaperone();
		}

		public function items(): BelongsToMany {
			return $this->belongsToMany(Item::class)->using(OrderItem::class);
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'status' => OrderStatus::class,
			];
		}
	}

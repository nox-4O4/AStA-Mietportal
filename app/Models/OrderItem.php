<?php

	namespace App\Models;

	use Carbon\CarbonImmutable;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\Pivot;

	/**
	 * @property int              $id
	 * @property int              $order_id
	 * @property Order            $order
	 * @property int              $item_id
	 * @property Item             $item
	 * @property int              $quantity
	 * @property CarbonImmutable  $start stores only date
	 * @property CarbonImmutable  $end   stores only date
	 * @property float            $original_price
	 * @property float            $price
	 * @property string           $comment
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
	 */
	class OrderItem extends Pivot {
		/**
		 * Indicates if the IDs are auto-incrementing.
		 *
		 * @var bool
		 */
		public $incrementing = true;

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'quantity',
			'start',
			'end',
			'original_price',
			'price',
			'comment',
		];

		public function order(): BelongsTo {
			return $this->belongsTo(Order::class);
		}

		public function item(): BelongsTo {
			return $this->belongsTo(Item::class);
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'start'          => 'date',
				'end'            => 'date',
				'quantity'       => 'int',
				'original_price' => 'float',
				'price'          => 'float',
			];
		}
	}

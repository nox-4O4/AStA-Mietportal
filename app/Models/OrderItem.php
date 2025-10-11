<?php

	namespace App\Models;

	use App\Contracts\PriceCalculation;
	use App\Events\InvoiceDataChanged;
	use Carbon\CarbonImmutable;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\Pivot;
	use Illuminate\Support\Facades\App;

	/**
	 * @property int              $id
	 * @property int              $order_id
	 * @property Order            $order
	 * @property int              $item_id
	 * @property Item             $item
	 * @property int              $quantity
	 * @property CarbonImmutable  $start       stores only date
	 * @property CarbonImmutable  $end         stores only date
	 * @property float            $original_price
	 * @property float            $price
	 * @property string           $comment
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
	 *
	 * @property-read string      $invoiceHash {@see OrderItem::invoiceHash()} for getter.
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

		static array $invoiceRelevantData = [
			'quantity',
			'start',
			'end',
			'original_price',
			'price',
			'item_id',
		];

		protected static function booted(): void {
			$priceCalculator = App::make(PriceCalculation::class);

			static::saving(function (OrderItem $orderItem) use ($priceCalculator) {
				$orderItem->original_price = $priceCalculator->calculatePrice($orderItem->item, $orderItem->start, $orderItem->end) * $orderItem->quantity;
				if(!isset($orderItem->price))
					$orderItem->price = $orderItem->original_price;

				foreach(static::$invoiceRelevantData as $field) {
					if($orderItem->$field != $orderItem->getOriginal($field)) {
						$orderItem->order->queueEvent(InvoiceDataChanged::class);
						break;
					}
				}
			});

			static::deleting(function (OrderItem $orderItem): void {
				$orderItem->order->queueEvent(InvoiceDataChanged::class);
			});
		}

		public function order(): BelongsTo {
			return $this->belongsTo(Order::class);
		}

		public function item(): BelongsTo {
			return $this->belongsTo(Item::class);
		}

		public function invoiceHash(): Attribute {
			return Attribute::get(fn() => sha1(implode("\0", $this->only(static::$invoiceRelevantData))));
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

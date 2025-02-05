<?php

	namespace App\Models;

	use App\Enums\OrderStatus;
	use DateTime;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\DB;

	/**
	 * @property int         $id
	 * @property OrderStatus $status
	 * @property float       $rate
	 * @property string      $event_name
	 * @property string      $note
	 * @property int         $customer_id
	 * @property Customer    $customer
	 * @property float       $deposit
	 * @property ?DateTime   $created_at
	 * @property ?DateTime   $updated_at
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

		public function comments(): HasMany {
			return $this->hasMany(Comment::class)->orderBy('created_at')->chaperone();
		}

		public function hasSinglePeriod(): bool {
			return $this->orderItems()->distinct(['start', 'end'])->count() <= 1;
		}

		public function commonStart(): Attribute {
			return Attribute::get(function (): ?DateTime {
				$result = DB::select(<<<SQL
					SELECT start col
					FROM order_item
					WHERE order_id = :order
					GROUP BY col
					HAVING COUNT(*) > 1
					ORDER BY COUNT(*) DESC
					LIMIT 1
					SQL,
					['order' => $this->id]
				);

				return $result ? new Carbon($result[0]->col) : null;
			})
			                ->shouldCache();
		}

		public function commonEnd(): Attribute {
			return Attribute::get(function (): ?DateTime {
				$result = DB::select(<<<SQL
					SELECT end col
					FROM order_item
					WHERE order_id = :order
					GROUP BY col
					HAVING COUNT(*) > 1
					ORDER BY COUNT(*) DESC
					LIMIT 1
					SQL,
					['order' => $this->id]
				);

				return $result ? new Carbon($result[0]->col) : null;
			})
			                ->shouldCache();
		}

		public function firstStart(): Attribute {
			return Attribute::get(fn(): ?DateTime => $this->orderItems()->reorder('start')->first()?->start)
			                ->shouldCache();
		}

		public function lastEnd(): Attribute {
			return Attribute::get(fn(): ?DateTime => $this->orderItems()->reorder('end', 'desc')->first()?->end)
			                ->shouldCache();
		}

		public function total(): Attribute {
			return Attribute::get(fn(): float => $this->orderItems()->sum('price') * $this->rate);
		}

		public function totalDiscount(): Attribute {
			return Attribute::get(function (): float {
				$originalPrice = $this->orderItems()->sum('original_price');
				$currentPrice  = $this->orderItems()->sum('price');

				return $originalPrice - $currentPrice  // absolute discount (disount on a per item basis)
				       + $currentPrice * (1 - $this->rate); // relative discount
			})->shouldCache();
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'status'  => OrderStatus::class,
				'rate'    => 'float',
				'deposit' => 'float',
			];
		}
	}

<?php

	namespace App\Models;

	use App\Enums\OrderStatus;
	use Carbon\CarbonImmutable;
	use Dompdf\Dompdf;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\Facades\DB;

	/**
	 * @property int              $id
	 * @property OrderStatus      $status
	 * @property float            $rate
	 * @property string           $event_name
	 * @property string           $note
	 * @property int              $customer_id
	 * @property Customer         $customer
	 * @property float            $deposit
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
	 *
	 * @property-read string      $orderSummaryPDF See {@see Order::orderSummaryPDF()} for getter.
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

		public function hasSinglePeriod(): Attribute {
			return Attribute::get(fn(): bool => $this->orderItems()->distinct(['start', 'end'])->count() == 1)
			                ->shouldCache();
		}

		/**
		 * When there is a distinct maximum occurence of a single start date, return this date.
		 *
		 * Examples: with dates `a`, `a`, `b`, `c` this function returns `a`;
		 *           with `a`, `a`, `b`, `b` it returns null;
		 *           with `a`, `a`, `b`, `b`, `b` it returns `b`;
		 *           with `a` it returns `a`;
		 *           with `a`, `b`, `c` it returns null.
		 *
		 * @return Attribute
		 */
		public function commonStart(): Attribute {
			return Attribute::get(function (): ?CarbonImmutable {
				$result = DB::select(<<<SQL
					WITH occurrences
						     AS (SELECT start    col,
						                COUNT(*) c
						         FROM order_item
						         WHERE order_id = :order
						         GROUP BY col)
					SELECT col,
					       COUNT(*) OVER (PARTITION BY c) = 1 isUnique
					FROM occurrences
					ORDER BY c DESC
					LIMIT 1
					SQL,
					['order' => $this->id]
				);

				return $result && $result[0]->isUnique
					? new CarbonImmutable($result[0]->col)
					: null;
			})->shouldCache();
		}

		/**
		 * When there is a distinct maximum occurence of a single end date, return this date.
		 * See {@see Order::commonStart()} for examples.
		 *
		 * @return Attribute
		 */
		public function commonEnd(): Attribute {
			return Attribute::get(function (): ?CarbonImmutable {
				$result = DB::select(<<<SQL
					WITH occurrences
						     AS (SELECT end      col,
						                COUNT(*) c
						         FROM order_item
						         WHERE order_id = :order
						         GROUP BY col)
					SELECT col,
					       COUNT(*) OVER (PARTITION BY c) = 1 isUnique
					FROM occurrences
					ORDER BY c DESC
					LIMIT 1
					SQL,
					['order' => $this->id]
				);

				return $result && $result[0]->isUnique
					? new CarbonImmutable($result[0]->col)
					: null;
			})->shouldCache();
		}

		public function firstStart(): Attribute {
			return Attribute::get(fn(): ?CarbonImmutable => $this->orderItems()->reorder('start')->first()?->start)
			                ->shouldCache();
		}

		public function lastEnd(): Attribute {
			return Attribute::get(fn(): ?CarbonImmutable => $this->orderItems()->reorder('end', 'desc')->first()?->end)
			                ->shouldCache();
		}

		public function total(): Attribute {
			return Attribute::get(fn(): float => $this->orderItems()->sum('price') * $this->rate);
		}

		public function itemDiscount(): Attribute {
			return Attribute::get(function (): float {
				$originalPrice = $this->orderItems()->sum('original_price');
				$currentPrice  = $this->orderItems()->sum('price');

				return $originalPrice - $currentPrice;
			})->shouldCache();
		}

		public function totalDiscount(): Attribute {
			return Attribute::get(function (): float {
				$originalPrice = $this->orderItems()->sum('original_price');
				$currentPrice  = $this->orderItems()->sum('price');

				return $originalPrice - $currentPrice  // absolute discount (disount on a per item basis)
				       + $currentPrice * (1 - $this->rate); // relative discount
			})->shouldCache();
		}

		protected function renderPDFTemplate(string $template): string {
			$dompdf = new Dompdf(['isPdfAEnabled' => true, 'chroot' => public_path()]);

			// remap sans-serif font to an embeddable font for PDF/A compatibility
			$font_metrics = $dompdf->getFontMetrics();
			$font_metrics->setFontFamily('sans-serif', $font_metrics->getFamily('DejaVu Sans'));

			$dompdf->setPaper('a4');
			$dompdf->loadHtml(Blade::render($template, ['order' => $this]));
			$dompdf->render();

			return $dompdf->output();
		}

		public function orderSummaryPDF(): Attribute {
			return Attribute::get(fn(): string => $this->renderPDFTemplate('pdfs.order-summary'))
			                ->shouldCache();
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

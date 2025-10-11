<?php

	namespace App\Models;

	use App\Enums\OrderStatus;
	use App\Events\InvoiceDataChanged;
	use App\Events\OrderEvent;
	use App\Notifications\InvoiceNotification;
	use App\Util\Helper;
	use Carbon\CarbonImmutable;
	use Dompdf\Canvas;
	use Dompdf\FontMetrics;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Support\Collection;
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
	 * @property bool             $invoice_required
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
	 *
	 * @property-read string      $orderSummaryPDF     See {@see Order::orderSummaryPDF()} for getter.
	 * @property-read string      $orderContractPDF    See {@see Order::orderContractPDF()} for getter.
	 * @property-read ?Invoice    $currentInvoice      See {@see Order::currentInvoice()} for getter.
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

		static array $invoiceRelevantData = [
			'rate',
			'event_name',
			'customer_id',
		];

		/**
		 * @var array<int, array<class-string<OrderEvent>, int>>
		 */
		private static array $queuedEvents = [];

		/**
		 * Queue an OrderEvent for later execution. Equal events (as determined by their FQCN) are only queued once.
		 *
		 * @param class-string<OrderEvent> $eventClass
		 *
		 * @return void
		 */
		public function queueEvent(string $eventClass): void {
			self::$queuedEvents[$this->id][$eventClass] = 1; // value not used
		}

		public function dispatchQueuedEvents(): void {
			$events = self::$queuedEvents[$this->id] ?? [];
			unset(self::$queuedEvents[$this->id]);

			/** @var class-string<OrderEvent> $eventClass */
			foreach($events as $eventClass => $_)
				app('events')->dispatch(new $eventClass($this));
		}

		protected static function booted(): void {
			static::saving(function (Order $order): void {
				foreach(static::$invoiceRelevantData as $field) {
					if($order->$field != $order->getOriginal($field)) {
						$order->queueEvent(InvoiceDataChanged::class);
						break;
					}
				}

				// invoiceRequired flag might change when order cancellation status changes
				$wasCancelledBefore = $order->getOriginal('status') == OrderStatus::CANCELLED;
				$isCancelledNow     = $order->status == OrderStatus::CANCELLED;
				if($wasCancelledBefore != $isCancelledNow)
					$order->queueEvent(InvoiceDataChanged::class);
			});
		}

		public function customer(): BelongsTo {
			return $this->belongsTo(Customer::class);
		}

		public function orderItems(): HasMany {
			return $this->hasMany(OrderItem::class)->orderBy('id')->chaperone();
		}

		public function items(): BelongsToMany {
			return $this->belongsToMany(Item::class, 'order_item')->using(OrderItem::class);
		}

		public function currentInvoice(): Attribute {
			return Attribute::get(function (): ?Invoice {
				$invoice = $this->invoices->first();
				if($invoice && !$invoice->cancelled && $invoice->content_hash == $this->calculateInvoiceHash())
					return $invoice;

				return null;
			}); // must not be cached to prevent stale values when hash or invoices change
		}

		public function invoices(): HasMany {
			return $this->hasMany(Invoice::class)->orderBy('version', 'desc')->chaperone();
		}

		public function comments(): HasMany {
			return $this->hasMany(Comment::class)->orderBy('created_at')->chaperone();
		}

		public function recentComments(?int $n): Collection {
			return $this->hasMany(Comment::class)->orderBy('created_at', 'DESC')->limit($n)->chaperone()->get()->reverse();
		}

		public function hasSinglePeriod(): Attribute {
			return Attribute::get(fn(): bool => $this->orderItems()->distinct(['start', 'end'])->count() == 1)
			                ->shouldCache();
		}

		public function calculateInvoiceHash(): string {
			$hashParts = [
				$this->customer->invoiceHash,
				$this->rate,
				$this->event_name,
				...$this->orderItems()
				        ->reorder('id')
				        ->get()
				        ->pluck('invoiceHash')
			];

			return sha1(implode("\0", $hashParts));
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

		public function calculatedDeposit(): Attribute {
			return Attribute::get(function (): float {
				$deposit = 0;
				foreach($this->orderItems as $orderItem)
					$deposit += $orderItem->item->deposit * $orderItem->quantity;

				return Helper::getSteppedDeposit($deposit);
			})->shouldCache();
		}

		public function orderSummaryPDF(): Attribute {
			return Attribute::get(fn(): string => Helper::renderPDFTemplate('pdfs.order-summary', ['order' => $this])->output())
			                ->shouldCache();
		}

		public function orderContractPDF(): Attribute {
			return Attribute::get(function (): string {
				$pdf = Helper::renderPDFTemplate('pdfs.order-contract', ['order' => $this]);

				// add page counter at bottom center of page
				$pdf->getCanvas()->page_script(
					function (int $pageNumber, int $pageCount, Canvas $canvas, FontMetrics $fontMetrics) {
						$text       = "Seite $pageNumber von $pageCount";
						$font       = $fontMetrics->getFont('sans-serif');
						$pageWidth  = $canvas->get_width();
						$pageHeight = $canvas->get_height();
						$size       = 8;
						$width      = $fontMetrics->getTextWidth($text, $font, $size);

						$canvas->text(($pageWidth - $width) / 2, $pageHeight - 32, $text, $font, $size);
					}
				);

				return $pdf->output();
			})->shouldCache();
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'status'           => OrderStatus::class,
				'rate'             => 'float',
				'deposit'          => 'float',
				'invoice_required' => 'bool',
			];
		}

		public function canBeCancelled(): bool {
			// An order can only be cancelled, when there are no non-cancelled invoices and cancellation notices have been sent for all notified invoices.
			// See concept in Rechnungsverwaltung.md for details.
			foreach($this->invoices as $invoice)
				if(!$invoice->cancelled || $invoice->notified && !$invoice->cancellation_notified)
					return false;

			return true;
		}

		public function hasValidInvoices(): bool {
			foreach($this->invoices as $invoice)
				if(!$invoice->cancelled)
					return true;

			return false;
		}

		public function canBeCompleted(): bool {
			// See concept in Rechnungsverwaltung.md for details.
			if($this->invoice_required)
				return false;

			if($this->notificationsMissing())
				return false;

			return true;
		}

		public function notificationsMissing(): bool {
			if(($currentInvoice = $this->currentInvoice) && $currentInvoice->total_amount != 0 && !$currentInvoice->notified)
				return true;

			foreach($this->invoices as $invoice)
				if($invoice->notified && $invoice->cancelled && !$invoice->cancellation_notified)
					return true;

			return false;
		}

		public function createInvoice(): bool {
			if($this->currentInvoice) {
				$this->queueEvent(InvoiceDataChanged::class); // when createInvoice() was called even though a valid invoice exists, the invoiceRequired flag is probably outdated
				return false;
			}

			foreach($this->invoices as $oldInvoice)
				if(!$oldInvoice->cancelled)
					$oldInvoice->cancel();

			if($this->orderItems->isEmpty())
				return false;

			$invoice = new Invoice();
			$invoice->order()->associate($this);
			$invoice->save(); // saving-event creates invoice file and fills other columns

			return true;
		}

		/**
		 * @param Invoice[] $invoices
		 * @param Invoice[] $cancellations
		 *
		 * @return void
		 */
		public function sendInvoiceNotification(array $invoices, array $cancellations): void {
			if(!$invoices && !$cancellations)
				return;

			$this->customer->notify(new InvoiceNotification($invoices, $cancellations));

			foreach($invoices as $invoice) {
				$invoice->notified = true;
				$invoice->save();
			}
			foreach($cancellations as $cancellation) {
				$cancellation->cancellation_notified = true;
				$cancellation->save();
			}
		}
	}

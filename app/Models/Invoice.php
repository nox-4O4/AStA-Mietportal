<?php

	namespace App\Models;

	use App\Events\InvoiceDataChanged;
	use App\Util\Helper;
	use Carbon\CarbonImmutable;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Support\Facades\Storage;
	use LogicException;
	use Stringable;

	/**
	 * @property int              $id
	 * @property ?int             $order_id
	 * @property int              $number
	 * @property int              $version
	 * @property ?Order           $order
	 * @property int              $customer_id
	 * @property Customer         $customer
	 * @property bool             $notified
	 * @property bool             $cancelled
	 * @property bool             $cancellation_notified
	 * @property string           $content_hash
	 * @property float            $total_amount
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
	 *
	 * @property-read string      $name                 See {@see Invoice::name()} for getter.
	 * @property-read string      $fileName             See {@see Invoice::fileName()} for getter.
	 * @property-read string      $filePath             See {@see Invoice::filePath()} for getter.
	 * @property-read ?string     $cancellationFileName See {@see Invoice::cancellationFileName()} for getter.
	 * @property-read ?string     $cancellationFilePath See {@see Invoice::cancellationFilePath()} for getter.
	 */
	class Invoice extends Model implements Stringable {

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'number',
			'version',
			'notified',
			'cancelled',
			'cancellation_notified',
			'content_hash',
			'total_amount',
		];

		public static function GeneratePreviewFor(Order $order): string {
			$tmpInvoice               = new Invoice();
			$tmpInvoice->order        = $order;
			$tmpInvoice->customer     = $order->customer;
			$tmpInvoice->number       = $order->id;
			$tmpInvoice->version      = count($order->invoices) + 1;
			$tmpInvoice->content_hash = $order->calculateInvoiceHash();
			$tmpInvoice->total_amount = $order->total;

			$pdf = Helper::renderPDFTemplate('pdfs.order-invoice', ['order' => $order, 'invoice' => $tmpInvoice, 'preview' => true]);
			return $pdf->output();
		}

		protected static function booted(): void {
			static::creating(function (Invoice $invoice): void {
				if(!$invoice->order)
					throw new LogicException('Cannot create an invoice without an order');

				$invoice->customer()->associate($invoice->order->customer);
				$invoice->number       = $invoice->order->id;
				$invoice->version      = count($invoice->order->invoices) + 1; // Unique index on (number, version) will prevent collisions. Still, invoices should not be created outside of transactions with locking to prevent data races.
				$invoice->content_hash = $invoice->order->calculateInvoiceHash();
				$invoice->total_amount = $invoice->order->total;
				$invoice->generateFile();

				$invoice->order->unsetRelation('invoices');
				$invoice->order->queueEvent(InvoiceDataChanged::class);
			});
		}

		public function order(): BelongsTo {
			return $this->belongsTo(Order::class);
		}

		public function customer(): BelongsTo {
			return $this->belongsTo(Customer::class);
		}

		public function name(): Attribute {
			return Attribute::get(fn(): string => "r{$this->number}v$this->version");
		}

		public function fileName(): Attribute {
			return Attribute::get(fn(): string => "$this->name.pdf");
		}

		public function filePath(): Attribute {
			return Attribute::get(fn(): string => Storage::disk('invoices')->path($this->fileName));
		}

		public function cancellationFileName(): Attribute {
			return Attribute::get(fn(): ?string => $this->cancelled ? "$this->name-storno.pdf" : null);
		}

		public function cancellationFilePath(): Attribute {
			return Attribute::get(fn(): ?string => $this->cancelled ? Storage::disk('invoices')->path($this->cancellationFileName) : null);
		}

		private function generateFile(): void {
			if(!$this->order)
				throw new LogicException('Cannot generate invoice without an order');

			$pdf = Helper::renderPDFTemplate('pdfs.order-invoice', ['order' => $this->order, 'invoice' => $this, 'preview' => false]);
			// TODO add Factur-X metadata, see https://github.com/dompdf/dompdf/wiki/PDFA-Support#zugferd--factur-x

			Storage::disk('invoices')->put($this->fileName, $pdf->output());
		}

		public function cancel(): void {
			if($this->cancelled)
				return;

			$pdf = Helper::renderPDFTemplate('pdfs.invoice-cancellation', ['invoice' => $this]);
			// TODO add Factur-X metadata, see https://github.com/dompdf/dompdf/wiki/PDFA-Support#zugferd--factur-x

			$this->cancelled = true;
			Storage::disk('invoices')->put($this->cancellationFileName, $pdf->output());

			$this->update();

			$this->order?->queueEvent(InvoiceDataChanged::class);
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'version'               => 'int',
				'total'                 => 'float',
				'notified'              => 'bool',
				'cancelled'             => 'bool',
				'cancellation_notified' => 'bool',
			];
		}
	}

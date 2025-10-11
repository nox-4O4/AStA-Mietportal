<?php

	namespace App\Events;

	use App\Models\Order;
	use Illuminate\Foundation\Events\Dispatchable;

	/**
	 * Event raised when invoice-relevant data has changed, and it should be re-evaluated whether a new invoice is required.
	 */
	class InvoiceDataChanged implements OrderEvent {
		use Dispatchable;

		public function __construct(public readonly Order $order) { }
	}

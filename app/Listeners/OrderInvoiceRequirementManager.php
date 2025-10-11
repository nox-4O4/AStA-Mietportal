<?php

	namespace App\Listeners;

	use App\Enums\OrderStatus;
	use App\Events\InvoiceDataChanged;

	class OrderInvoiceRequirementManager {

		public function handle(InvoiceDataChanged $event): void {
			// see Rechnungsverwaltung.md for details
			$invoiceRequired = $event->order->status != OrderStatus::CANCELLED && // Don't require invoices for cancelled orders.
			                   !$event->order->currentInvoice && ( // When there is a valid and matching invoice, everything is fine.

				                   // We always require a valid invoice when total amount is not zero.
				                   $event->order->total != 0 ||

				                   // When total amount is zero, we only require a new invoice when the most recent invoice does not match the order.
				                   // As $mostRecentInvoice is not cancelled and order does not have a currentInvoice it means that the content hash
				                   // of $mostRecentInvoice does not match the order invoice hash.
				                   ($mostRecentInvoice = $event->order->invoices->first()) && !$mostRecentInvoice->cancelled
			                   );

			if($event->order->invoice_required != $invoiceRequired) {
				$event->order->invoice_required = $invoiceRequired;
				$event->order->save();
			}
		}
	}

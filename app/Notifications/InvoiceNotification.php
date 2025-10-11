<?php

	namespace App\Notifications;

	use App\Models\Invoice;
	use App\Util\Helper;
	use Arr;
	use Illuminate\Notifications\Messages\MailMessage;
	use Illuminate\Notifications\Notification;
	use LogicException;

	class InvoiceNotification extends Notification {

		/**
		 * @param array<Invoice> $invoices
		 * @param array<Invoice> $cancellations
		 */
		public function __construct(
			private readonly array $invoices,
			private readonly array $cancellations
		) {
			if(!$invoices && !$cancellations)
				throw new LogicException('Invoice missing for notification');
		}

		public function via(object $notifiable): array {
			return ['mail'];
		}

		public function toMail(object $notifiable): MailMessage {
			$anyInvoice = ($this->invoices[0] ?? $this->cancellations[0]); // TODO use array_first when on PHP 8.5+

			if($this->invoices && $this->cancellations) { // cancellation(s) and invoice(s) exist
				$subject = 'Rechnung';
				$lines = [
					"Deine Bestellung #$anyInvoice->number im AStA-Mietportal wurde aktualisiert.",

					'Anbei erhältst du die ' .
					(count($this->invoices) > 1 ? 'neuen Rechnungen ' : 'neue Rechnung ') .
					Arr::join(array_map(fn(Invoice $invoice) => $invoice->name, $this->invoices), ', ', ' und ') .
					' sowie die ' .
					(count($this->cancellations) > 1 ? 'Stornierungen der alten Rechnungen ' : 'Stornierung der alten Rechnung ') .
					'(' . Arr::join(array_map(fn(Invoice $invoice) => $invoice->name, $this->cancellations), ', ', ' und ') . ').',
				];

			} else if($this->invoices) { // only invoice(s) exist
				$subject = 'Rechnung';
				$lines = [
					'Anbei erhältst du die ' .
					(count($this->invoices) > 1 ? 'Rechnungen ' : 'Rechnung ') .
					Arr::join(array_map(fn(Invoice $invoice) => $invoice->name, $this->invoices), ', ', ' und ') .
					" zu deiner Bestellung #$anyInvoice->number im AStA-Mietportal.",
				];

			} else { // only cancellation(s) exist
				$subject = 'Rechnungsstornierung';
				$lines = [
					"Deine Bestellung #$anyInvoice->number im AStA-Mietportal wurde aktualisiert.",

					(count($this->cancellations) > 1 ? 'Die Rechnungen ' : 'Die Rechnung ') .
					Arr::join(array_map(fn(Invoice $invoice) => $invoice->name, $this->cancellations), ', ', ' und ') .
					(count($this->cancellations) > 1 ? ' wurden' : ' wurde') . ' storniert, ' .
					'anbei erhältst du die ' .
					(count($this->cancellations) > 1 ? 'Stornierungen.' : 'Stornierung.'),
				];
			}

			return (new MailMessage)
				->markdown('mail.notification')
				->subject("$subject zu deiner Bestellung #$anyInvoice->number")
				->greeting('Hallo ' . Helper::EscapeMarkdown($anyInvoice->customer->forename) . '!')
				->lines($lines)
				->attachMany(Arr::mapWithKeys($this->invoices, fn(Invoice $invoice) => [
					$invoice->filePath => ['as' => $invoice->fileName, 'mime' => 'application/pdf']
				]))
				->attachMany(Arr::mapWithKeys($this->cancellations, fn(Invoice $invoice) => [
					$invoice->cancellationFilePath => ['as' => $invoice->cancellationFileName, 'mime' => 'application/pdf']
				]));
		}
	}

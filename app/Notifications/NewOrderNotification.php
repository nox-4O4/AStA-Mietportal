<?php

	namespace App\Notifications;

	use App\Models\Order;
	use App\Util\Helper;
	use Illuminate\Notifications\Messages\MailMessage;
	use Illuminate\Notifications\Notification;

	class NewOrderNotification extends Notification {

		public function __construct(private readonly Order $order) {
		}

		public function via(object $notifiable): array {
			return ['mail'];
		}

		public function toMail(object $notifiable): MailMessage {
			return (new MailMessage)
				->markdown('mail.order-notification', ['order' => $this->order])
				->subject("Neue Bestellung #{$this->order->id} von {$this->order->customer->name}")
				->line('Es ist eine neue Bestellung von ' . Helper::EscapeMarkdown($this->order->customer->name) . ' eingegangen. Die Bestellbestätigung befindet sich im Anhang.')
				->action('Bestellung öffnen', route('dashboard.orders.view', $this->order->id))
				->attachData($this->order->orderSummaryPDF, "Eingangsbestätigung #{$this->order->id}.pdf", ['mime' => 'application/pdf']);
		}
	}

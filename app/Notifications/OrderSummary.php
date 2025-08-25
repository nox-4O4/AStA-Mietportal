<?php

	namespace App\Notifications;

	use App\Models\Content;
	use App\Models\Customer;
	use App\Models\Order;
	use App\Util\Helper;
	use Illuminate\Notifications\Messages\MailMessage;
	use Illuminate\Notifications\Notification;
	use Illuminate\Support\HtmlString;

	class OrderSummary extends Notification {

		readonly Content $mailContent;

		public function __construct(private readonly Order $order) {
			$this->mailContent = Content::fromName('mail.orderSummary');
		}

		public function via(object $notifiable): array {
			return ['mail'];
		}

		public function toMail(object $notifiable): MailMessage {
			$customer = $notifiable instanceof Customer ? $notifiable : $this->order->customer;

			return (new MailMessage)
				->markdown('mail.notification')
				->subject("Bestellübersicht zu deiner Bestellung #{$this->order->id}")
				->greeting('Hallo ' . Helper::EscapeMarkdown($customer->forename) . '!')
				->line(new HtmlString($this->mailContent->render()))
				->attachData($this->order->orderSummaryPDF, "Bestellübersicht #{$this->order->id}.pdf", ['mime' => 'application/pdf']);
		}
	}

<?php

	namespace App\Notifications;

	use App\Models\User;
	use App\Util\Helper;
	use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
	use Illuminate\Notifications\Messages\MailMessage;

	class AccountCreated extends ResetPasswordNotification {

		public function __construct(#[\SensitiveParameter] $token, private readonly User $user) { parent::__construct($token); }

		protected function buildMailMessage($url): MailMessage {
			return (new MailMessage)
				->markdown('mail.notification')
				->subject('AStA-Mietportal: Benutzeraccount angelegt')
				->greeting('Hallo ' . Helper::EscapeMarkdown($this->user->forename) . '!')
				->line('Für dich wurde ein Benutzeraccount für das AStA-Mietportal erstellt.')
				->line('Dein Benutzername lautet: ' . Helper::EscapeMarkdown($this->user->username))
				->action('Passwort festlegen', $url)
				->line('Dieser Link ist ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' Minunten lang gültig.')
				->line('Solltest du dein Passwort später festlegen wollen, kannst du dir mit der [Passwort vergessen](' . route('password.forgot') . ')-Funktion jederzeit eine neue Passwort-Reset-E-Mail zuschicken lassen.');
		}
	}

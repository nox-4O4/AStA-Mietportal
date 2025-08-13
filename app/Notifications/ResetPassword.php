<?php

	namespace App\Notifications;

	use App\Models\User;
	use App\Util\Helper;
	use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
	use Illuminate\Notifications\Messages\MailMessage;

	class ResetPassword extends ResetPasswordNotification {
		public function __construct(#[\SensitiveParameter] $token, private readonly User $user) { parent::__construct($token); }

		protected function buildMailMessage($url): MailMessage {
			return (new MailMessage)
				->markdown('mail.notification')
				->subject('AStA-Mietportal: Zurücksetzen des Passworts')
				->greeting('Hallo ' . Helper::EscapeMarkdown($this->user->forename) . '!')
				->line('Du erhältst diese Nachricht, da wir für deinene Account eine Anfrage zum Zurücksetzen des Passworts erhalten haben.')
				->action('Passwort zurücksetzen', $url)
				->line('Dieser Link ist ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' Minunten lang gültig.')
				->line('Falls du das Zurücksetzen deines Passworts nicht angefragt hast, kannst du diese Nachricht löschen und brauchst nichts weiter zu machen.');
		}
	}

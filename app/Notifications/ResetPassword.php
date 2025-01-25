<?php

	namespace App\Notifications;

	use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
	use Illuminate\Notifications\Messages\MailMessage;
	use Illuminate\Support\Facades\Lang;

	class ResetPassword extends ResetPasswordNotification {
		protected function buildMailMessage($url) {
			return (new MailMessage)
				->markdown('mail.notification')
				->subject('AStA-Mietportal: Zurücksetzen des Passworts')
				->line('Du erhältst diese Nachricht, da wir für deinene Account eine Anfrage zum Zurücksetzen des Passworts erhalten haben.')
				->action('Passwort zurücksetzen', $url)
				->line('Dieser Link ist ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' Minunten lang gültig.')
				->line('Falls du das Zurücksetzen deines Passworts nicht angefragt hast, kannst du diese Nachricht löschen und brauchst nichts weiter zu machen.');
		}
	}

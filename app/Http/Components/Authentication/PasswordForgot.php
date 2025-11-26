<?php

	namespace App\Http\Components\Authentication;

	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Facades\Password;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Attributes\Validate;
	use Livewire\Component;
	use Symfony\Component\Mailer\Exception\TransportException;
	use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

	#[Title('Passwort vergessen')]
	#[Layout('layouts.login')]
	class PasswordForgot extends Component {
		#[Validate('required|string|email:strict')]
		public string $email = '';

		/**
		 * Send a password reset link to the provided email address.
		 */
		public function sendPasswordResetLink(): void {
			$this->validate();

			try {
				$status = Password::sendResetLink($this->only('email'));
			} catch(TransportException|UnexpectedResponseException $ex) {
				report($ex);
				$status = 'passwords.notification_failed';
			}

			switch($status) {
				case Password::RESET_LINK_SENT:
				case Password::INVALID_USER: // same message for successfull password reset and invalid user to prevent e-mail address disclosure of registered users (only prevents trivial case; they can still exploit the throttle mechanism or use a timing attack)
					session()->flash('status', __(Password::RESET_LINK_SENT));
					$this->redirectRoute('login', navigate: true);
					break;

				default:
					$this->addError('email', __($status));
			}
		}

		public function render(): View {
			return view('components.authentication.password-forgot');
		}
	}

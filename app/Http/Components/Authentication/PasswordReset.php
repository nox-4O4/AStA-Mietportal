<?php

	namespace App\Http\Components\Authentication;

	use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Password;
	use Illuminate\Validation\Rules\Password as PasswordRule;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Attributes\Url;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	#[Title('Passwort zurücksetzen')]
	#[Layout('layouts.login')]
	class PasswordReset extends Component {

		#[Validate]
		#[Locked]
		public string $token = '';

		#[Url]
		#[Validate]
		#[Locked]
		public string $email = '';

		#[Validate]
		public string $password = '';

		#[Validate]
		public string $passwordConfirmation = '';

		/**
		 * Performs a password reset.
		 */
		public function resetPassword(): void {
			$this->validate();

			$status = Password::reset(
				$this->only('email', 'password', 'token'),
				function ($user) {
					$user->forceFill([
						                 'password'       => Hash::make($this->password),
						                 'remember_token' => null, // invalidate old remember me token
					                 ])
					     ->save();

					event(new PasswordResetEvent($user));
				}
			);

			switch($status) {
				case Password::INVALID_TOKEN:
				case Password::INVALID_USER:
					$this->addError('token', 'invalid');
					break;

				case Password::PASSWORD_RESET:
					session()->flash('status', __($status));
					$this->redirectRoute('login', navigate: true);
			}
		}

		public function render(): View {
			if(is_null($user = Password::getUser($this->only('email'))) || !Password::getRepository()->exists($user, $this->token))
				$this->addError('token', 'invalid');

			return view('components.authentication.password-reset')->with('user', $user);
		}

		protected function rules(): array {
			return [
				'token'                => 'required',
				'email'                => ['required', 'string', 'email:strict'],
				'password'             => ['required', 'string', PasswordRule::default()],
				'passwordConfirmation' => ['required', 'string', 'confirmed:password'],
			];
		}
	}

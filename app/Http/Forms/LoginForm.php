<?php

	namespace App\Http\Forms;

	use Illuminate\Auth\Events\Lockout;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\RateLimiter;
	use Illuminate\Support\Str;
	use Illuminate\Validation\ValidationException;
	use Livewire\Attributes\Validate;
	use Livewire\Form;

	class LoginForm extends Form {
		#[Validate('required|string')]
		public string $username = '';

		#[Validate('required|string')]
		public string $password = '';

		#[Validate('boolean')]
		public bool $rememberme = false;

		/**
		 * Attempt to authenticate the request's credentials.
		 *
		 * @throws ValidationException
		 */
		public function authenticate(): void {
			$this->ensureIsNotRateLimited();

			if(!Auth::attempt($this->only(['username', 'password']), $this->rememberme)) {
				RateLimiter::hit($this->throttleKey());
				$this->reset('password');

				throw ValidationException::withMessages(['form.login' => 'Der Loginvorgang ist fehlgeschlagen. Möglicherweise stimmen die Authentifizierungsdaten nicht überein oder der Benutzer ist deaktiviert.']);
			}

			Auth::user()->updateLastLogin();

			RateLimiter::clear($this->throttleKey());
		}

		/**
		 * Ensure the authentication request is not rate limited.
		 */
		protected function ensureIsNotRateLimited(): void {
			if(!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
				return;
			}

			event(new Lockout(request()));
			$seconds = RateLimiter::availableIn($this->throttleKey());
			$this->reset('password');

			throw ValidationException::withMessages(['form.login' => "Es sind zu viele fehlgeschlagene Loginversuche aufgetreten. Versuchen Sie es in $seconds Sekunden erneut."]);
		}

		/**
		 * Get the authentication rate limiting throttle key.
		 */
		protected function throttleKey(): string {
			return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
		}
	}

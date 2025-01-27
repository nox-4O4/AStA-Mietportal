<?php

	namespace App\Console\Commands;

	use App\Enums\UserRole;
	use App\Models\User;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Str;
	use Illuminate\Validation\Rule;
	use Illuminate\Validation\Rules\Password;
	use Validator;

	class CreateUser extends Command {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'user:create';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Creates a new admin user';

		/**
		 * Execute the console command.
		 */
		public function handle() {
			$forename = $this->askAndValidate(
				'What is your first name?',
				fn($value): bool => strlen(Str::slug($value, '')),
				'Invalid or empty name specified. Please try again.'
			);

			$surname = $this->askAndValidate(
				'What is your last name?',
				fn($value): bool => strlen(Str::slug($value, '')),
				'Invalid or empty name specified. Please try again.'
			);

			do {
				if(isset($email)) {
					$this->warn('An user with this address already exists. Please specify a different email address.');
				}

				$email = $this->askAndValidate(
					'What is your email address?',
					fn($value): bool => Validator::make(['email' => $value], ['email' => Rule::email()->rfcCompliant(true)])->passes(),
					'Invalid email address specified. Please try again.'
				);
			} while(User::where('email', $email)->exists());

			$password = $this->askAndValidate(
				'Please enter a strong password',
				fn($value): bool => Validator::make(['password' => $value], ['password' => Password::default()])->passes(),
				'Password too weak. It should be at least 8 characters.',
				true
			);

			$this->askAndValidate(
				'Please repeat your password',
				fn($value): bool => $value == $password,
				'Passwords do not match. Please try again.',
				true
			);

			$username  = Str::slug($forename) . '.' . Str::slug($surname);
			$increment = '';
			while(User::where('username', $username . $increment)->exists()) {
				$increment = $increment ? $increment + 1 : 1;
			}
			$username .= $increment;

			new User(
				[
					'username' => $username,
					'forename' => $forename,
					'surname'  => $surname,
					'email'    => $email,
					'password' => Hash::make($password),
					'role'     => UserRole::ADMIN,
				]
			)->save();

			$this->info('User created successfully. Your username is: ' . $username);
		}

		private function askAndValidate(string $question, callable $validator, string $errorMessage, bool $secret = false): string {
			do {
				if(isset($value)) {
					$this->warn($errorMessage);
				}

				$value = $secret
					? $this->secret($question)
					: trim($this->ask($question));
			} while(!$validator($value));

			return $value;
		}
	}

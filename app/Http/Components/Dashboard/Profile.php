<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\User;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Str;
	use Illuminate\Validation\Rule;
	use Illuminate\Validation\Rules\Password;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Profil')]
	#[Layout('layouts.dashboard')]
	class Profile extends Component {
		public string $forename;
		public string $surname;
		public string $email;
		public string $username;

		public string $currentPassword;
		public string $newPassword;
		public string $newPasswordConfirmation;

		public function mount(): void {
			$this->loadProfileData();
		}

		public function updateProfile(): void {
			$values = $this->validate(
				[
					'forename' => ['required', 'string'],
					'surname'  => ['required', 'string'],
					'email'    => [
						'required',
						'string',
						'email:strict',
						Rule::unique(User::class)->ignore(auth()->user()->id)
					],
					'username' => [
						Rule::excludeIf(auth()->user()->cannot('manage-users')),
						'required',
						'string',
						Rule::unique(User::class)->ignore(auth()->user()->id),
					],
				]
			);

			auth()->user()->update($values);
			$this->loadProfileData();
		}

		public function updatePassword(): void {
			$this->validate(
				[
					'newPassword'             => ['required', 'string', Password::default()],
					'newPasswordConfirmation' => ['required', 'string', 'same:newPassword'],
					'currentPassword'         => ['required', 'string', 'current_password'],
				]
			);

			auth()->user()
			      ->forceFill([
				                  'password'       => Hash::make($this->newPassword),
				                  'remember_token' => Str::random(60), // invalidate old remember me token
			                  ])
			      ->save();

			$this->reset('newPassword', 'newPasswordConfirmation', 'currentPassword');
			session()->flash('message', 'Dein Passwort wurde geändert.');
		}

		private function loadProfileData(): void {
			$this->fill(auth()->user()->only(['forename', 'surname', 'email', 'username']));
		}
	}

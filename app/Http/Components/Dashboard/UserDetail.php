<?php

	namespace App\Http\Components\Dashboard;

	use App\Enums\UserRole;
	use App\Models\User;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Support\Facades\Password;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Benutzerverwaltung')]
	#[Layout('layouts.dashboard')]
	class UserDetail extends Component {
		use TrimWhitespaces;

		#[Locked]
		public User $user;

		public string   $forename;
		public string   $surname;
		public string   $email;
		public string   $username;
		public int      $role;
		public bool     $enabled;
		public bool|int $confirmation;

		public function mount() {
			$this->fill($this->user->only(['forename', 'surname', 'email', 'username', 'enabled']));
			$this->role = $this->user->role->value;
		}

		public function updateUser() {
			$values = $this->validate(
				[
					'forename'     => ['required', 'string'],
					'surname'      => ['required', 'string'],
					'email'        => [
						'required',
						'string',
						'email:strict',
						Rule::unique(User::class)->ignore($this->user->id)
					],
					'username'     => [
						'required',
						'string',
						Rule::unique(User::class)->ignore($this->user->id),
					],
					'role'         => [
						'required',
						Rule::enum(UserRole::class),
					],
					'enabled'      => [
						'required',
						'bool',
					],
					'confirmation' =>
						Rule::when($this->user->id == auth()->user()->id && ($this->role != UserRole::ADMIN->value || !$this->enabled), [
							'required',
							'bool',
							'accepted',
						])
				]
			);

			$this->user->update($values);

			if($this->user->id == auth()->user()->id) {
				auth()->setUser($this->user);

				if(!auth()->user()->enabled || !auth()->user()->can('manage-users')) {
					$this->confirmation = 2; // hack: we're using any non-bool value (to still get a validation error when the old value is used) that evaluates to true when used as checkbox model binding (so it won't flash unchecked before redirecting the user)
					$this->redirectRoute(config('app.dashboard.defaultRoute'), navigate: true);
				} else {
					$this->confirmation = false;
				}
			}
		}

		public function deleteUser(): void {
			$this->user->delete();

			session()->flash('status', "Der Benutzer „{$this->username}“ wurde erfolgreich gelöscht.");
			$this->redirectRoute('users.list', navigate: true);
		}

		public function sendPasswordMail(): void {
			$status = Password::sendResetLink($this->only('email'));

			if($status == Password::RESET_LINK_SENT) {
				session()->flash('mailSuccess', 'Es wurde eine Passwort-Reset-E-Mail an den Benutzer gesendet.');
			} else {
				session()->flash('mailError', __($status));
			}
		}
	}

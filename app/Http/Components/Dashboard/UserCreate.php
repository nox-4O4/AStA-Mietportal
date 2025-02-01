<?php

	namespace App\Http\Components\Dashboard;

	use App\Enums\UserRole;
	use App\Models\User;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Support\Facades\Password;
	use Illuminate\Support\Str;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Benutzer erstellen')]
	#[Layout('layouts.dashboard')]
	class UserCreate extends Component {
		use TrimWhitespaces;

		public string  $forename;
		public string  $surname;
		public string  $email;
		public ?string $username = null;
		public int     $role;
		public bool    $enabled  = true;

		public function createUser() {
			$this->validate();

			$autoname = false;
			if(!$this->username) {
				$autoname       = true;
				$this->username = Str::slug($this->forename) . '.' . Str::slug($this->surname);
				$increment      = '';
				while(User::where('username', $this->username . $increment)->exists()) {
					$increment = $increment ? $increment + 1 : 1;
				}
				$this->username .= $increment;
			}

			new User($this->all())->save();

			if($this->enabled) {
				$status = Password::sendResetLink($this->only('email'));

				switch($status) {
					case Password::RESET_LINK_SENT:
						session()->flash('status.success', 'Der Benutzer wurde angelegt und sollte eine E-Mail zum Festlegen des Passworts erhalten haben.' . ($autoname ? " Benutzername: $this->username" : ''));
						break;
					default:
						session()->flash('status.error', 'Der Benutzer wurde angelegt, es konnte jedoch keine E-Mail zum Festlegen des Passworts gesendet werden: ' . __($status));
				}
			} else {
				session()->flash('status.success', 'Der Benutzer wurde angelegt.' . ($autoname ? " Benutzername: $this->username" : ''));
			}

			$this->redirect(route('dashboard.users.list'), true);
		}

		public function rules() {
			return [
				'forename' => ['required', 'string'],
				'surname'  => ['required', 'string'],
				'email'    => [
					'required',
					'string',
					'email:strict',
					Rule::unique(User::class)
				],
				'username' => [
					'nullable',
					'string',
					Rule::unique(User::class),
				],
				'role'     => [
					'required',
					Rule::enum(UserRole::class),
				],
				'enabled'  => [
					'required',
					'bool',
				],
			];
		}
	}

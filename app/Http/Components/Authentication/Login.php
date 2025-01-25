<?php

	namespace App\Http\Components\Authentication;

	use App\Http\Forms\LoginForm;
	use Illuminate\Support\Facades\Session;
	use Livewire\Attributes\Layout;
	use Livewire\Component;

	#[Layout('layouts.login')]
	class Login extends Component {
		public LoginForm $form;

		public function login(): void {
			$this->validate();

			$this->form->authenticate();

			Session::regenerate();

			$this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
		}
	}

<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\User;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Benutzerverwaltung')]
	#[Layout('layouts.dashboard')]
	class UserDetail extends Component {

		#[Locked]
		public User $user;

		public function render() {
			return view('components.dashboard.user-detail');
		}
	}

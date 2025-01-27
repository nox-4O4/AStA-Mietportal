<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\User;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Benutzerverwaltung')]
	#[Layout('layouts.dashboard')]
	class UserList extends Component {

		#[Computed]
		public function users() {
			return User::all()->sortByDesc(['last_login', 'updated_at']);
		}
	}

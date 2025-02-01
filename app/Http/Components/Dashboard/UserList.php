<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\User;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Benutzerverwaltung')]
	#[Layout('layouts.dashboard')]
	class UserList extends Component {

		#[Computed]
		public function users(): Collection {
			return User::orderBy('last_login', 'desc')
			           ->orderBy('updated_at', 'desc')
			           ->get();
		}
	}

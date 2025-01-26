<?php

	namespace App\Http\Components\Dashboard;

	use Livewire\Attributes\Computed;
	use Livewire\Component;

	class OrderCountBadge extends Component {

		#[Computed]
		public function count() {
			return 42;
		}
	}

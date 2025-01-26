<?php

	namespace App\Http\Components;

	use Livewire\Attributes\Computed;
	use Livewire\Component;

	class OrderCountBadge extends Component {

		#[Computed]
		public function count() {
			return 42;
		}
	}

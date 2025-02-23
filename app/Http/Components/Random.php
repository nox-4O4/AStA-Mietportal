<?php

	namespace App\Http\Components;

	use Livewire\Component;

	/** Dummy component to aid in debugging by visualizing component updates. It displays a random value. */
	class Random extends Component {

		public function render(): string {
			return '<span>' . bin2hex(random_bytes(4)) . '</span>';
		}
	}

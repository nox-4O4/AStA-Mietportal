<?php

	namespace App\Http\Components\Dashboard;

	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Platzhalter')]
	#[Layout('layouts.dashboard')]
	class Dummy extends Component {
		public function render(): View {
			return view('components.dashboard.dummy');
		}
	}

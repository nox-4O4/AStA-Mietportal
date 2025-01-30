<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\Item;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Artikel')]
	#[Layout('layouts.dashboard')]
	class ItemList extends Component {

		#[Computed]
		public function items() {
			return Item::all();
		}
	}

<?php

	namespace App\Http\Components\Dashboard\Items;

	use App\Models\Item;
	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Artikel')]
	#[Layout('layouts.dashboard')]
	class ItemList extends Component {

		#[Computed]
		public function items() {
			return Item::all()->sortBy('name', SORT_NATURAL); // not using database for sorting as we want to get mutated name (containing optional group name) and use natural sort
		}

		public function render(): View {
			return view('components.dashboard.items.item-list');
		}
	}

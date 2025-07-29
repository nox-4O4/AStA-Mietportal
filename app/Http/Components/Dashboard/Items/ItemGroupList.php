<?php

	namespace App\Http\Components\Dashboard\Items;

	use App\Models\ItemGroup;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Artikelgruppen')]
	#[Layout('layouts.dashboard')]
	class ItemGroupList extends Component {

		#[Computed]
		public function groups(): Collection {
			return ItemGroup::orderBy('name')->get();
		}

		public function render(): View {
			return view('components.dashboard.items.item-group-list');
		}
	}

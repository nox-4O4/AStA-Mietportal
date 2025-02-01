<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\ItemGroup;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Artikelgruppen')]
	#[Layout('layouts.dashboard')]
	class ItemGroupList extends Component {

		#[Computed]
		public function groups() {
			return ItemGroup::all();
		}
	}

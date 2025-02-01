<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\ItemGroup;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class ItemGroupDetail extends Component {

		#[Locked]
		public ItemGroup $group;
	}

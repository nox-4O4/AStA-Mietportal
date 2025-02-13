<?php

	namespace App\Http\Components\Dashboard\Settings;

	use App\Models\Content;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Inhalte')]
	#[Layout('layouts.dashboard')]
	class ContentList extends Component {
		public function render() {
			return view('components.dashboard.settings.content-list');
		}

		#[Computed]
		public function contents(): Collection {
			return Content::all();
		}
	}

<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\DisabledDate;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Einstellungen')]
	#[Layout('layouts.dashboard')]
	class Settings extends Component {

		#[Computed]
		public function disabledDates(): Collection {
			return DisabledDate::orderBy('start')
			                   ->orderBy('end')
			                   ->get();
		}

		public function render() {
			return view('components.dashboard.settings');
		}

		public function removeDisabledDate(int $id): void {
			DisabledDate::destroy($id);
		}
	}

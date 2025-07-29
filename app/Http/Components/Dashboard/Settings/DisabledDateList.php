<?php

	namespace App\Http\Components\Dashboard\Settings;

	use App\Models\DisabledDate;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Deaktivierte Zeiträume')]
	#[Layout('layouts.dashboard')]
	class DisabledDateList extends Component {

		#[Computed]
		public function disabledDates(): Collection {
			return DisabledDate::orderBy('start')
			                   ->orderBy('end')
			                   ->get();
		}

		public function render(): View {
			return view('components.dashboard.settings.disabled-date-list');
		}

		public function removeDisabledDate(int $id): void {
			DisabledDate::destroy($id);
		}
	}

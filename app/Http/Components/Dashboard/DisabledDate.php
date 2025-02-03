<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\DisabledDate as DisabledDateModel;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class DisabledDate extends Component {

		#[Locked]
		public ?DisabledDateModel $disabledDate = null;

		#[Validate('required|date')]
		public string $start;

		#[Validate('required|date|after_or_equal:start')]
		public string $end;

		#[Validate('sometimes|string')]
		public string $comment = '';

		#[Validate('sometimes|string')]
		public string $site_notice = '';

		#[Validate('required|boolean')]
		public bool $active = true;

		public function mount() {
			if($this->disabledDate) {
				$this->fill($this->disabledDate->only(['comment', 'site_notice', 'active']));
				$this->start = $this->disabledDate->start->format('Y-m-d');
				$this->end   = $this->disabledDate->end->format('Y-m-d');
			}
		}

		public function render() {
			return view('components.dashboard.disabled-date')
				->title('Deaktivierten Zeitraum ' . ($this->disabledDate ? 'bearbeiten' : 'anlegen'));
		}

		public function save() {
			$this->validate();

			if($this->disabledDate) {
				session()->flash('status.success', 'Deaktivierten Zeitraum aktualisiert.');
				$this->disabledDate->fill($this->except(['disabledDate']))->update();
			} else {
				session()->flash('status.success', 'Deaktivierten Zeitraum gespeichert.');
				new DisabledDateModel($this->except(['disabledDate']))->save();
			}

			$this->redirectRoute('dashboard.settings.view', navigate: true);
		}

		public function delete(int $id): void {
			DisabledDateModel::destroy($id);

			session()->flash('status.success', 'Deaktivierten Zeitraum gelöscht.');
			$this->redirectRoute('dashboard.settings.view', navigate: true);
		}
	}

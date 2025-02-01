<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\ItemGroup;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class ItemGroupDetail extends Component {

		#[Locked]
		public ItemGroup $group;

		#[Validate('required|string|unique:' . ItemGroup::class)]
		public string $name;

		#[Validate('nullable|string')]
		public ?string $description;

		public function mount(): void {
			$this->fill($this->group->only('name', 'description'));
		}

		public function updateGroup(): void {
			$this->validate();
			$this->group->update($this->only('name', 'description'));
		}

		public function deleteGroup(): void {
			$this->group->delete();
			session()->flash('status.success', "Die Gruppe „{$this->group->name}“ wurde gelöscht.");
			$this->redirectRoute('dashboard.groups.list', navigate: true);
		}
	}

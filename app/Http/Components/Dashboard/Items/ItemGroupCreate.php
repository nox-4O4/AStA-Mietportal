<?php

	namespace App\Http\Components\Dashboard\Items;

	use App\Models\ItemGroup;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class ItemGroupCreate extends Component {

		#[Validate('required|string|unique:' . ItemGroup::class)]
		public string $name;

		#[Validate('nullable|string')]
		public ?string $description = null;

		public function createGroup(): void {
			$this->validate();

			$group = new ItemGroup($this->only(['name', 'description']));
			$group->save();

			session()->flash('status.success', 'Gruppe erfolgreich angelegt.');
			$this->redirect(route('dashboard.groups.edit', $group->id));
		}
	}

<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\Item;
	use App\Models\ItemGroup;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Url;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class ItemGroupCreate extends Component {

		#[Url(as: 'initialItem')]
		public ?string $initialItemId = null;

		#[Validate('required|string|unique:' . ItemGroup::class)]
		public string $name;

		#[Validate('nullable|string')]
		public ?string $description = null;

		public function createGroup(): void {
			$this->validate();

			$group = new ItemGroup($this->only(['name', 'description']));
			$group->save();

			if(request()->get)
				$this->initialItemId = (int) $this->initialItemId;

			if($this->initialItemId && $item = Item::find($this->initialItemId)) {
				$item->itemGroup()->associate($group)->save();

				session()->flash('status.success', "Die Gruppe „{$group->name}“ wurde erfolgreich angelegt und dem Artikel zugewiesen.");
				$this->redirect(route('dashboard.items.edit', $this->initialItemId));
			} else {
				session()->flash('status.success', 'Gruppe erfolgreich angelegt.');
				$this->redirect(route('dashboard.groups.edit', $group->id));
			}
		}
	}

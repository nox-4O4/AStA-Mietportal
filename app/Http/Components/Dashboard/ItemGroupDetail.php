<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\Item;
	use App\Models\ItemGroup;
	use Illuminate\Support\Collection;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class ItemGroupDetail extends Component {

		#[Locked]
		public ItemGroup $group;

		public string $name;

		public ?string $description;
		public ?int    $newItem = null;

		public function mount(): void {
			$this->fill($this->group->only('name', 'description'));
		}

		#[Computed]
		public function hasItems(): bool {
			return $this->group->items->count() > 0;
		}

		#[Computed]
		public function addableItems(): Collection {
			return Item::fromQuery(<<<SQL
				SELECT items.*
				FROM items
				LEFT JOIN item_groups ON items.item_group_id = item_groups.id
				WHERE NOT item_group_id <=> :currentGroup
				SQL,
				['currentGroup' => $this->group->id]
			)->sortBy( // cannot use database for sorting as we need natural sort
				[
					fn(Item $a, Item $b) => ($a->item_group_id !== null) <=> // makes sure items without group come first
					                        ($b->item_group_id !== null),
					'name', // uses mutated (composite) name
				], SORT_NATURAL
			);
		}

		public function updateGroup(): void {
			$values = $this->validate(
				[
					'name'        => ['required', 'string', Rule::unique(ItemGroup::class)->ignore($this->group->id)],
					'description' => ['nullable', 'string'],
				]
			);

			$this->group->update($values);
		}

		public function deleteGroup(): void {
			$this->group->delete();
			session()->flash('status.success', "Die Gruppe „{$this->group->name}“ wurde gelöscht.");
			$this->redirectRoute('dashboard.groups.list', navigate: true);
		}

		public function removeItem(int $itemId): void {
			if(!($item = Item::find($itemId)) || $item->itemGroup?->id != $this->group->id)
				return;

			$item->itemGroup()
			     ->dissociate()
			     ->save();
		}

		public function addItem(): void {
			$this->validate(
				[
					'newItem' => [
						'required',
						'integer',
						Rule::unless($this->newItem == -1, Rule::exists(Item::class, 'id')),
					]
				]);

			if($this->newItem == -1) {
				$this->redirectRoute('dashboard.items.create', ['itemGroupPrefill' => $this->group->id], navigate: true);

			} else {
				Item::find($this->newItem)
				    ->itemGroup()
				    ->associate($this->group)
				    ->save();
			}

			$this->newItem = null;
		}
	}

<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\Item;
	use App\Models\ItemGroup;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Support\Collection;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Attributes\Url;
	use Livewire\Component;

	#[Title('Artikel anlegen')]
	#[Layout('layouts.dashboard')]
	class ItemCreate extends Component {
		use TrimWhitespaces;

		public string $name;
		public string $description = '';
		public bool   $available   = true;
		public bool   $visible     = true;
		public bool   $keepStock   = true;
		public int    $stock;
		public float  $price;
		public int    $deposit;

		public string $itemGroup = '';

		#[Url(except: '')]
		#[Locked]
		public string $itemGroupPrefill = '';

		public function mount() {
			if($this->itemGroupPrefill && $group = ItemGroup::find($this->itemGroupPrefill))
				$this->itemGroup = $group->id;
			else
				$this->itemGroupPrefill = '';
		}

		#[Computed]
		public function groups(): Collection {
			return ItemGroup::orderBy('name')->get();
		}

		public function render() {
			return view('components.dashboard.item-detail');
		}

		public function saveItem() {
			$this->validate(
				[
					'name'        => ['required', 'string'],
					'description' => ['string'],
					'available'   => ['boolean'],
					'visible'     => ['boolean'],
					'keepStock'   => ['boolean'],
					'stock'       => ['required_if_accepted:keepStock', Rule::unless(!$this->keepStock, ['integer', 'gt:0'])],
					'price'       => ['required', 'numeric'],
					'deposit'     => ['required', 'integer'],
					'itemGroup'   => ['sometimes', Rule::exists(ItemGroup::class, 'id')],
				]
			);

			$item         = new Item($this->except(['keepStock', 'stock', 'itemGroup']));
			$item->amount = $this->keepStock ? $this->stock : 0;
			if($this->itemGroup)
				$item->item_group_id = $this->itemGroup;

			$item->save();

			if($this->itemGroup && $this->itemGroupPrefill == $this->itemGroup) {
				session()->flash('status.success', "Artikel „{$this->name}“ erfolgreich angelegt und der Gruppe hinzugefügt.");
				$this->redirectRoute('dashboard.groups.edit', $this->itemGroup);
			} else {
				session()->flash('status.success', "Artikel „{$this->name}“ erfolgreich angelegt.");
				$this->redirectRoute('dashboard.items.edit', $item->id);
			}
		}
	}

<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\Item;
	use App\Models\ItemGroup;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Support\Collection;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Artikel anlegen')]
	#[Layout('layouts.dashboard')]
	class ItemCreate extends Component {
		use TrimWhitespaces;

		public string $name;
		public string $description      = '';
		public bool   $available        = true;
		public bool   $visible          = true;
		public bool   $keepStock        = true;
		public int    $stock;
		public float  $price;
		public int    $deposit;
		public string $initialItemGroup = '';

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
					'name'             => ['required', 'string'],
					'description'      => ['string'],
					'available'        => ['boolean'],
					'visible'          => ['boolean'],
					'keepStock'        => ['boolean'],
					'stock'            => ['required_if_accepted:keepStock', Rule::unless(!$this->keepStock, ['integer', 'gt:0'])],
					'price'            => ['required', 'numeric'],
					'deposit'          => ['required', 'integer'],
					'initialItemGroup' => ['sometimes', Rule::exists(ItemGroup::class, 'id')],
				]
			);

			$item         = new Item($this->except(['keepStock', 'stock', 'initialItemGroup']));
			$item->amount = $this->keepStock ? $this->stock : 0;
			if($this->initialItemGroup)
				$item->item_group_id = $this->initialItemGroup;

			$item->save();

			session()->flash('status.success', "Artikel „{$this->name}“ erfolgreich angelegt.");
			$this->redirectRoute('dashboard.items.edit', $item->id);
		}
	}

<?php

	namespace App\Http\Components\Dashboard;

	use App\Models\Item;
	use App\Models\ItemGroup;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Support\Arr;
	use Illuminate\Support\Collection;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class ItemDetail extends Component {
		use TrimWhitespaces;

		#[Locked]
		public Item $item;

		public string $name;
		public string $description;
		public bool   $available;
		public bool   $visible;
		public bool   $keepStock;
		public int    $stock;
		public float  $price;
		public int    $deposit;

		#[Locked]
		public Collection $groups;
		public int|string $itemGroup;

		public function mount(Item $item) {
			$this->item        = $item;
			$this->name        = $item->rawName();
			$this->description = $item->description;
			$this->available   = $item->available;
			$this->visible     = $item->visible;
			$this->keepStock   = $item->amount > 0;
			$this->stock       = $item->amount;
			$this->price       = $item->price;
			$this->deposit     = $item->deposit;
			$this->groups      = ItemGroup::all()->sortBy('name');
			$this->itemGroup   = $item->itemGroup?->id ?? 'none';
		}

		public function render() {
			return view('components.dashboard.item-detail')
				->title("Artikel „{$this->item->name}“ bearbeiten");
		}

		public function updateItem() {
			$values = $this->validate(
				[
					'name'        => ['required', 'string'],
					'description' => ['string'],
					'available'   => ['boolean'],
					'visible'     => ['boolean'],
					'keepStock'   => ['boolean'],
					'stock'       => ['required_if_accepted:keepStock', Rule::unless(!$this->keepStock, ['integer', 'gt:0'])],
					'price'       => ['required', 'numeric'],
					'deposit'     => ['required', 'integer'],
				]
			);

			$this->item->fill(Arr::except($values, ['keepStock', 'stock']));
			$this->item->amount = $this->keepStock ? $this->stock : 0;
			$this->item->update();
		}

		public function updateGroup() {
			$this->validate(['itemGroup' => 'required']);

			if($this->itemGroup == 'new') {
				$this->redirectRoute('dashboard.groups.create', ['initialItem' => $this->item->id], navigate: true);

			} else if($this->itemGroup == 'none') {
				$this->item->itemGroup()->associate(null);
				$this->item->update();

			} else {
				$this->validate(['itemGroup' => Rule::exists(ItemGroup::class, 'id')]);

				$this->item->itemGroup()->associate(ItemGroup::find($this->itemGroup));
				$this->item->update();
			}
		}
	}

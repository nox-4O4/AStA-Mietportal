<?php

	namespace App\Http\Components\Shop;

	use App\Contracts\PriceCalculation;
	use App\Models\Item as ItemModel;
	use App\Models\ItemGroup;
	use Illuminate\Http\RedirectResponse;
	use Livewire\Attributes\Layout;
	use Livewire\Component;

	#[Layout('layouts.shop')]
	class Item extends Component {

		public ?ItemModel $item  = null;
		public ?ItemGroup $group = null;
		public ?string    $slug  = null;

		public ItemModel|ItemGroup $element;

		protected PriceCalculation $priceCalculator;

		public function boot(PriceCalculation $priceCalculator): void {
			$this->priceCalculator = $priceCalculator;
		}

		public function mount(): void {
			if($this->item && $this->group || !$this->item && !$this->group) // should never happen as exactly one of those parameters is required in route
				$this->redirectRoute('shop');

			// make sure slug matches
			else if($this->item && $this->slug != $this->item->slug)
				// Workaround: Livewire currently does not support 301 redirects using built-in redirect() function, so we resort to throwing a HttpResponseException exception
				abort(new RedirectResponse(route('shop.item.view', ['item' => $this->item, 'slug' => $this->item->slug]), 301));

			else if($this->group && $this->slug != $this->group->slug)
				abort(new RedirectResponse(route('shop.itemGroup.view', ['group' => $this->group, 'slug' => $this->group->slug]), 301));

			$this->element = $this->item ?? $this->group;
		}

		public function render() {
			return view('components.shop.item')
				->title($this->element->name);
		}
	}

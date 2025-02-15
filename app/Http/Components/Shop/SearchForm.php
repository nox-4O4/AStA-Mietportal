<?php

	namespace App\Http\Components\Shop;

	use App\Models\Item;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Url;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	class SearchForm extends Component {
		use TrimWhitespaces;

		#[Validate('string')]
		#[Url(as: 'suche', history: true, except: '')]
		public string $search = '';

		public function render(): View {
			return view('components.shop.search-form');
		}

		public function performSearch(): void {
			$this->validate();

			$this->redirectRoute('shop', $this->search !== '' ? ['suche' => $this->search] : '', navigate: true);
		}

		#[Computed]
		public function items(): Collection {
			return Item::where('visible', true)->get()->sortBy('name', SORT_NATURAL);
		}
	}

<?php

	namespace App\Http\Components\Shop;

	use App\Traits\HasCartItems;
	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\On;
	use Livewire\Component;

	#[On('cart-changed')]
	class CartBadge extends Component {
		use HasCartItems;

		public bool $newItem = false;
		public bool $cleared = false;

		public function render(): View {
			return view('components.shop.cart-badge');
		}

		#[On('item-added-to-cart')]
		public function onItemAddedToCart(): void {
			$this->newItem = true;
		}

		#[On('cart-cleared')]
		public function onCartCleared(): void {
			$this->cleared = true;
		}
	}

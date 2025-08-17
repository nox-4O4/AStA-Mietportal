<?php

	namespace App\Http\Components\Shop;

	use App\Contracts\PriceCalculation;
	use App\Models\DTOs\CartItem;
	use App\Repositories\CartRepository;
	use App\Traits\HasCartItems;
	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	/**
	 * @property-read array<CartItem> $cartItemsSorted See {@see Cart::cartItemsSorted()} for getter.
	 * @property-read float           $totalAmount     See {@see Cart::totalAmount()} for getter.
	 */
	#[Title('Warenkorb')]
	#[Layout('layouts.shop')]
	class Cart extends Component {
		use HasCartItems;

		protected PriceCalculation $priceCalculator;
		protected CartRepository   $cartRepository;

		public function boot(PriceCalculation $priceCalculator, CartRepository $cartRepository): void {
			$this->priceCalculator = $priceCalculator;
			$this->cartRepository  = $cartRepository;
		}

		public function render(): View {
			$this->clearValidation();
			$this->setErrorBag($this->cartRepository->getItemValidationErrors());

			return view('components.shop.cart');
		}

		public function resetCart(): void {
			$this->cartRepository->clearCartItems();
			$this->items = [];
			$this->dispatch('cart-changed');
			$this->dispatch('cart-cleared');
		}

		#[Computed]
		public function totalAmount(): float {
			return $this->cartRepository->totalAmount();
		}

		#[Computed]
		public function cartItemsSorted(): array {
			return $this->cartRepository->getCartItemsSorted();
		}

		#[Computed]
		public function possibleDiscountRate(): float {
			return $this->cartRepository->discountRate();
		}

		public function updatedItems($amount, ?string $key, CartRepository $repository): void {
			if($key === null || !is_int($amount))
				return;

			$keyParts = explode('.', $key, 2);
			if(count($keyParts) != 2 || empty($this->items[$keyParts[0]]) || $keyParts[1] != 'amount')
				return;

			$id = $keyParts[0];

			if(is_array($this->items[$id]))
				$this->items = CartItem::collect($this->items);

			if($amount > 0) {
				$this->items[$id]->amount = $amount;
				$repository->updateCartItem($id, $this->items[$id]);
			} else {
				unset($this->items[$id]);
				$repository->updateCartItem($id, null);

				$this->dispatch('cart-changed');
				if(!$this->items)
					$this->dispatch('cart-cleared');
			}
		}

		public function removeItem(string $id, CartRepository $repository): void {
			if(isset($this->items[$id])) {
				unset($this->items[$id]);
				$repository->updateCartItem($id, null);

				$this->dispatch('cart-changed');
				if(!$this->items)
					$this->dispatch('cart-cleared');
			}
		}
	}

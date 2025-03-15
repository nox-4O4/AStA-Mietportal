<?php

	namespace App\Http\Components\Shop;

	use App\Contracts\PriceCalculation;
	use App\Exceptions\AmountExceededException;
	use App\Models\DisabledDate;
	use App\Models\DTOs\CartItem;
	use App\Repositories\CartRepository;
	use App\Traits\HasCartItems;
	use Carbon\CarbonImmutable;
	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Component;

	/**
	 * @property-read array<CartItem> $cartItemsSorted See {@see Cart::cartItemsSorted()} for getter.
	 * @property-read float           $totalAmount     See {@see Cart::totalAmount()} for getter.
	 */
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
			$this->validateItems();
			return view('components.shop.cart');
		}

		public function resetCart(CartRepository $repository): void {
			$repository->clearCartItems();
			$this->items = [];
			$this->dispatch('cart-changed');
			$this->dispatch('cart-cleared');
		}

		#[Computed]
		public function totalAmount(): float {
			$total = 0;
			foreach($this->items as $cartItem)
				$total += $this->priceCalculator->calculatePrice($cartItem->item, $cartItem->start, $cartItem->end) * $cartItem->amount;

			return $total;
		}

		#[Computed]
		public function cartItemsSorted(): array {
			$itemBuckets = [];

			foreach($this->items as $id => $item)
				$itemBuckets[$item->item->id][$id] = $item;

			foreach($itemBuckets as &$items)
				uasort($items, fn(CartItem $a, CartItem $b) => [$a->start, $a->end, $a->comment] <=> [$b->start, $b->end, $b->comment]);

			return array_merge(...$itemBuckets);
		}

		private function validateItems(): void {
			$this->clearValidation();

			$minDate = CarbonImmutable::now()->startOfDay()->addDays(config('shop.booking_ahead_days_min'));
			$maxDate = config('shop.booking_ahead_days_max')
				? CarbonImmutable::now()->startOfDay()->addDays(config('shop.booking_ahead_days_max'))
				: null;

			$validItems = [];
			$errors     = [];

			foreach($this->cartItemsSorted as $id => $cartItem) {
				if($cartItem->end->lt($cartItem->start))
					$errors["items.$id.range"][] = 'Das Ende darf nicht vor dem Start liegen.';

				else if($cartItem->start->lt($minDate))
					$errors["items.$id.range"][] = "Der Beginn darf nicht vor dem {$minDate->formatLocalDate()} liegen.";

				else if($maxDate && $cartItem->end->gt($maxDate))
					$errors["items.$id.range"][] = "Das Ende darf nicht nach dem {$maxDate->formatLocalDate()} liegen.";

				else if(DisabledDate::overlapsWithRange($cartItem->start, $cartItem->end))
					$errors["items.$id.range"][] = 'Der Mietservice steht in diesem Zeitraum nicht zur Verfügung.';

				else if($cartItem->amount === null)
					$errors["items.$id.amount"][] = 'Bitte die Menge angeben.';

				else if(($available = $cartItem->item->getMaximumAvailabilityInRange($cartItem->start, $cartItem->end)) !== true && $cartItem->amount > $available)
					$errors["items.$id.amount"][] = $available ? 'Es sind nicht genügend Artikel verfügbar.' : 'Dieser Artikel ist nicht verfügbar.';

				else try {
					$this->cartRepository->validateAvailabilityForNewItem($cartItem, $validItems);

					$validItems[$id] = $cartItem;
				} catch(AmountExceededException $e) {
					$errors["items.$id.amount"][] = "Der Artikel befindet sich bereits im Warenkorb, wodurch der verfügbare Bestand am {$e->date->formatLocalDate()} überschritten wird.";
				}
			}

			$this->setErrorBag($errors);
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

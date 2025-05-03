<?php

	namespace App\Traits;

	use App\Models\DTOs\CartItem;
	use App\Repositories\CartRepository;
	use Illuminate\Support\Facades\Gate;
	use Livewire\Attributes\Locked;

	trait HasCartItems {
		/**
		 * @var array<CartItem>
		 */
		public array $items;

		#[Locked]
		public string $cartId;

		public function bootHasCartItems(CartRepository $repository): void {
			$sessionItems      = $repository->getCartItems();
			$validSessionItems = $this->removeInvalidItems($sessionItems);
			if(count($sessionItems) != count($validSessionItems))
				$repository->setCartItems($validSessionItems);

			$this->items = $validSessionItems;
		}

		public function mountHasCartItems(CartRepository $repository): void {
			$this->cartId = $repository->getCartId();
		}

		/**
		 * @param array<CartItem> $items
		 *
		 * @return array<CartItem>
		 */
		protected function removeInvalidItems(array $items): array {
			return array_filter(
				$items,
				fn(CartItem $element, $key) => preg_match('/^[a-f0-9]+$/', $key) && // prevent invalid keys when local storage was tampered with as we're outputting keys unescaped in JS context
				                               $element->item && Gate::allows('view', $element->item) &&
				                               ($element->amount === null || $element->amount > 0),
				ARRAY_FILTER_USE_BOTH
			);
		}

		public function updatedHasCartItems($name, $value, CartRepository $repository): void {
			if($name != 'items')
				return;

			if($this->cartId != $repository->getCartId()) {
				// different cart between component creation and now
				// two possibilities:
				// - checkout was performed (this changes cart id)
				// - session expired while page was open in browser

				if(!$repository->getOldCartId()) {
					// session probably expired, update cart id and continue with updating items
					$repository->setCartId($this->cartId);
				} else {
					// - session not expired but cart id mismatch
					// - oldId equals to current id (unless tab was offline for some time and in another tab of the same session multiple checkouts occured)
					// -> prevent update of items (this keeps data from session) and update cart id

					$this->cartId = $repository->getCartId();

					return;
				}
			} // else: cart matches, perform update

			$newItems   = CartItem::collect($value);
			$validItems = $this->removeInvalidItems($newItems);

			$this->items = $validItems;
			$repository->setCartItems($validItems);
		}
	}

<?php

	namespace App\Traits;

	use App\Models\DTOs\CartItem;
	use App\Repositories\CartRepository;
	use Illuminate\Support\Facades\Gate;

	trait HasCartItems {
		/**
		 * @var array<CartItem>
		 */
		public array $items;

		public function bootHasCartItems(CartRepository $repository): void {
			$sessionItems      = $repository->getCartItems();
			$validSessionItems = $this->removeInvalidItems($sessionItems);
			if(count($sessionItems) != count($validSessionItems))
				$repository->setCartItems($validSessionItems);

			$this->items = $validSessionItems;
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

			$newItems   = CartItem::collect($value);
			$validItems = $this->removeInvalidItems($newItems);

			$this->items = $validItems;
			$repository->setCartItems($validItems);
		}
	}

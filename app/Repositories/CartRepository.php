<?php

	namespace App\Repositories;

	use App\Exceptions\AmountExceededException;
	use App\Models\DTOs\CartItem;
	use App\Models\DTOs\ItemAvailability;
	use App\Models\Item;
	use Illuminate\Support\Arr;

	class CartRepository {

		/**
		 * @return array<CartItem>
		 * @noinspection PhpDocMissingThrowsInspection this won't throw; PHPStorm is confused about the implementation
		 */
		public function getCartItems(): array {
			return session()->get('cart.items', []);
		}

		public function addCartItem(CartItem $cartItem): void {
			$items = $this->getCartItems();

			do {
				$key = bin2hex(random_bytes(8));
			} while(isset($items[$key]));
			$items[$key] = $cartItem;

			$this->setCartItems($items);
		}

		public function setCartItems(array $cartItems): void {
			session()->put('cart.items', $cartItems);
		}

		public function clearCartItems(): void {
			$this->setCartItems([]);
		}

		public function updateCartItem(string $id, ?CartItem $cartItem): void {
			if($cartItem !== null)
				session()->put("cart.items.$id", $cartItem);
			else
				session()->forget("cart.items.$id");
		}

		public function containsItem(Item $item): bool {
			return array_any($this->getCartItems(), fn($cartItem) => $cartItem->item->id == $item->id);
		}

		/**
		 * @throws AmountExceededException
		 */
		public function validateAvailabilityForNewItem(CartItem $newItem, ?array $existingItemsToConsider = null): void {
			if(!$newItem->item->amount)
				return;

			$existingItems = array_filter($existingItemsToConsider ?? $this->getCartItems(), fn(CartItem $cartItem) => $cartItem->item->id == $newItem->item->id && $cartItem->amount !== null);
			if(!$existingItems)
				return;

			// we only need to check dates where booked amount changes
			$amountChanges = [
				new ItemAvailability($newItem->start, $newItem->amount),
				new ItemAvailability($newItem->end->addDay(), -$newItem->amount), // adding one day as amount is still blocked for end day. Only on next day amount shall be available for new orders.
			];
			foreach($existingItems as $existingItem) {
				$amountChanges[] = new ItemAvailability($existingItem->start, $existingItem->amount);
				$amountChanges[] = new ItemAvailability($existingItem->end->addDay(), -$existingItem->amount);
			}
			// when date is equal, returning of items must come first
			usort($amountChanges, fn(ItemAvailability $a, ItemAvailability $b) => [$a->date->timestamp, $a->available] <=> [$b->date->timestamp, $b->available]);

			$availabilities = Arr::mapWithKeys(
				$newItem->item->getAvailabilitiesInRange(false, from: $newItem->start, to: $newItem->end),
				fn(ItemAvailability $itemAvailability) => [$itemAvailability->date->format('c'), $itemAvailability->available]
			);

			$booked = 0;
			foreach($amountChanges as $amountChange) {
				$booked += $amountChange->available;
				if($booked > ($availabilities[$amountChange->date->format('c')] ?? $newItem->item->amount)) {
					throw  AmountExceededException::forDate($amountChange->date);
				}
			}
		}
	}

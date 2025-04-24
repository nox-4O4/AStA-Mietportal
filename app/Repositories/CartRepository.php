<?php

	namespace App\Repositories;

	use App\Contracts\PriceCalculation;
	use App\Exceptions\AmountExceededException;
	use App\Models\DisabledDate;
	use App\Models\DTOs\CartItem;
	use App\Models\DTOs\ItemAvailability;
	use App\Models\Item;
	use Carbon\CarbonImmutable;
	use Illuminate\Support\Arr;

	class CartRepository {
		private static bool $fresh = false;

		public function __construct(private readonly PriceCalculation $priceCalculator) { }

		/**
		 * @return array<CartItem>
		 * @noinspection PhpDocMissingThrowsInspection this won't throw; PHPStorm is confused about the implementation
		 */
		public function getCartItems(): array {
			/** @var array<CartItem> $items */
			$items = session()->get('cart.items', []);

			if(!self::$fresh) {
				foreach($items as $cartItem)
					$cartItem->item->refresh();

				self::$fresh = true;
			}

			return $items;
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
					throw AmountExceededException::forDate($amountChange->date);
				}
			}
		}

		/**
		 * @return array<CartItem>
		 */
		public function getCartItemsSorted(): array {
			$itemBuckets = [];

			foreach($this->getCartItems() as $id => $item)
				$itemBuckets[$item->item->id][$id] = $item;

			foreach($itemBuckets as &$items)
				uasort($items, fn(CartItem $a, CartItem $b) => [$a->start, $a->end, $a->comment] <=> [$b->start, $b->end, $b->comment]);

			return array_merge(...$itemBuckets);
		}

		public function getItemValidationErrors(): array {
			$minDate = CarbonImmutable::now()->startOfDay()->addDays(config('shop.booking_ahead_days_min'));
			$maxDate = config('shop.booking_ahead_days_max')
				? CarbonImmutable::now()->startOfDay()->addDays(config('shop.booking_ahead_days_max'))
				: null;

			$validItems = [];
			$errors     = [];

			foreach($this->getCartItemsSorted() as $id => $cartItem) {
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
					$this->validateAvailabilityForNewItem($cartItem, $validItems);

					$validItems[$id] = $cartItem;
				} catch(AmountExceededException $e) {
					$errors["items.$id.amount"][] = "Der Artikel befindet sich bereits im Warenkorb, wodurch der verfügbare Bestand am {$e->date->formatLocalDate()} überschritten wird.";
				}
			}

			return $errors;
		}

		public function totalAmount(): float {
			$total = 0;
			foreach($this->getCartItems() as $cartItem)
				$total += $this->priceCalculator->calculatePrice($cartItem->item, $cartItem->start, $cartItem->end) * $cartItem->amount;

			return $total;
		}

		public function getHash(): string {
			$itemHash = '';
			foreach($this->getCartItems() as $cartItem)
				$itemHash .= $cartItem->getHash();

			return sha1($itemHash);
		}
	}

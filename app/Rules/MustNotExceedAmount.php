<?php

	namespace App\Rules;

	use App\Exceptions\AmountExceededException;
	use App\Models\DTOs\CartItem;
	use App\Models\Item;
	use App\Repositories\CartRepository;
	use Carbon\CarbonImmutable;
	use Closure;
	use Illuminate\Contracts\Validation\ValidationRule;

	class MustNotExceedAmount implements ValidationRule {

		public function __construct(protected readonly Item $item, protected readonly CarbonImmutable $start, protected readonly CarbonImmutable $end) { }

		/**
		 * Run the validation rule.
		 *
		 * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
		 */
		public function validate(string $attribute, mixed $value, Closure $fail): void {
			// check if available amount suffices in general
			$available = $this->item->getMaximumAvailabilityInRange($this->start, $this->end);

			if($available !== true && $value > $available) {
				$fail('Es sind nicht genügend Artikel verfügbar.');
				return;
			}

			// check if available amount suffices when considering existing cart items
			try {
				app()->make(CartRepository::class)->validateAvailabilityForNewItem(new CartItem($this->item, $this->start, $this->end, $value, ''));
			} catch(AmountExceededException $e) {
				$fail("Dieser Artikel befindet sich bereits im Warenkorb, wodurch der verfügbare Bestand am {$e->date->formatLocalDate()} überschritten werden würde.");
			}
		}
	}

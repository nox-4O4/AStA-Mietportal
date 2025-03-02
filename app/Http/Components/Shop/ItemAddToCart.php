<?php

	namespace App\Http\Components\Shop;

	use App\Contracts\PriceCalculation;
	use App\Models\DisabledDate;
	use App\Models\Item as ItemModel;
	use Carbon\Carbon;
	use Closure;
	use Date;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Collection;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	/**
	 * @property-read Carbon|\Illuminate\Support\Carbon      $minDate       See {@see ItemAddToCart::minDate()} for getter. \Illuminate\Support\Carbon type in declaration is only used for getting auto-completion of macro functions in IDE.
	 * @property-read Carbon|\Illuminate\Support\Carbon|null $maxDate       See {@see ItemAddToCart::maxDate()} for getter. \Illuminate\Support\Carbon type in declaration is only used for getting auto-completion of macro functions in IDE.
	 * @property-read Collection<DisabledDate>               $disabledDates See {@see ItemAddToCart::disabledDates()} for getter.
	 */
	class ItemAddToCart extends Component {
		#[Validate]
		public ?Carbon $start = null;

		#[Validate]
		public ?Carbon $end = null;

		// no validate attribute for amount as we only want to validate amount when adding items to cart, not during update. Otherwise, validation message for amount might appear when changing date.
		public int $amount;

		#[Validate]
		public string $comment = '';

		#[Locked]
		public ItemModel $item;

		protected PriceCalculation $priceCalculator;

		public function mount(): void {
			$this->start = session()->get('cart.lastStartDate');
			$this->end   = session()->get('cart.lastEndDate');
		}

		public function boot(PriceCalculation $priceCalculator): void {
			$this->priceCalculator = $priceCalculator;
		}

		protected function rules(): array {
			return [
				'start'   => [
					'required',
					'date',
					"after_or_equal:$this->minDate",
				],
				'end'     => [
					'required',
					'date',
					'after_or_equal:start',
					Rule::unless($this->maxDate == null, "before_or_equal:$this->maxDate"),
					fn(string $attribute, mixed $value, Closure $fail) => $this->start && $this->end &&
					                                                      DisabledDate::overlapsWithRange($this->start, $this->end) &&
					                                                      $fail('Der Mietservice steht in diesem Zeitraum nicht zur Verfügung.')
				],
				'amount'  => [
					'required',
					'gt:0',
					Rule::when($this->start && $this->end, function () {
						$available = $this->item->getMaximumAvailabilityInRange($this->start, $this->end);

						return $available === true
							? []
							: ["lte:$available"];
					}),
				],
				'comment' => [
					'nullable',
					'string',
					'max:4096',
				],
			];
		}

		protected function messages(): array {
			return [
				'start.after_or_equal' => "Der Beginn darf nicht vor dem {$this->minDate->formatLocalDate()} liegen.",
				'end.before_or_equal'  => $this->maxDate ? "Das Ende darf nicht nach dem {$this->maxDate->formatLocalDate()} liegen." : 'Das Ende darf nicht nach dem :date liegen.',
				'amount.lte'           => 'Es sind nicht genügend Artikel verfügbar.',
			];
		}

		public function render(): View {
			return view('components.shop.item-add-to-cart');
		}

		#[Computed]
		public function minDate(): Carbon {
			return Carbon::now()->startOfDay()->addDays(config('shop.booking_ahead_days_min'));
		}

		#[Computed]
		public function maxDate(): ?Carbon {
			return config('shop.booking_ahead_days_max')
				? Carbon::now()->startOfDay()->addDays(config('shop.booking_ahead_days_max'))
				: null;
		}

		#[Computed]
		public function disabledDates(): Collection {
			return DisabledDate::where('end', '>=', Date::now())
			                   ->where('active', true)
			                   ->get();
		}

		public function addToCart(): void {
			$this->validate();

			// TODO add to cart

			session()->put('cart.lastStartDate', $this->start);
			session()->put('cart.lastEndDate', $this->end);

			$this->reset('start', 'end', 'amount', 'comment');

			$this->dispatch('item-added-to-cart');
			session()->flash(
				'cart.status.success', /** @lang Blade */
				'Artikel erfolgreich in den Warenkorb gelegt.<br><a href="' . route('shop.cart') . '" wire:navigate><i class="fa-solid fa-arrow-right me-1"></i>Zum Warenkorb</a>'
			);
			session()->flash('cart.status.raw');
		}
	}

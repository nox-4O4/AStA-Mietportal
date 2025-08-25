<?php

	namespace App\Http\Components\Shop;

	use App\Contracts\PriceCalculation;
	use App\Models\DisabledDate;
	use App\Models\DTOs\CartItem;
	use App\Models\Item as ItemModel;
	use App\Repositories\CartRepository;
	use App\Rules\MustNotExceedAmount;
	use App\Traits\TrimWhitespaces;
	use Carbon\CarbonImmutable;
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
	 * @property-read CarbonImmutable          $minDate       See {@see ItemAddToCart::minDate()} for getter.
	 * @property-read CarbonImmutable|null     $maxDate       See {@see ItemAddToCart::maxDate()} for getter.
	 * @property-read Collection<DisabledDate> $disabledDates See {@see ItemAddToCart::disabledDates()} for getter.
	 */
	class ItemAddToCart extends Component {
		use TrimWhitespaces;

		#[Validate]
		public ?CarbonImmutable $start = null;

		#[Validate]
		public ?CarbonImmutable $end = null;

		// no validate attribute for amount as we only want to validate amount when adding items to cart, not during update. Otherwise, validation message for amount might appear when changing date.
		public int $amount;

		#[Validate]
		public string $comment = '';

		#[Locked]
		public ItemModel $item;

		protected PriceCalculation $priceCalculator;
		protected CartRepository   $cartRepository;

		public function mount(): void {
			$this->start = session()->get('cart.lastStartDate');
			$this->end   = session()->get('cart.lastEndDate');
		}

		public function boot(PriceCalculation $priceCalculator, CartRepository $cartRepository): void {
			$this->priceCalculator = $priceCalculator;
			$this->cartRepository  = $cartRepository;
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
					                                                      DisabledDate::anyOverlapsWithRange($this->start, $this->end) &&
					                                                      $fail('Der Mietservice steht in diesem Zeitraum nicht zur Verfügung.')
				],
				'amount'  => [
					'required',
					'gt:0',
					Rule::when($this->start && $this->end, fn() => new MustNotExceedAmount($this->item, $this->start, $this->end)),
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
			];
		}

		public function render(): View {
			return view('components.shop.item-add-to-cart');
		}

		#[Computed]
		public function minDate(): CarbonImmutable {
			return CarbonImmutable::now()->startOfDay()->addDays(config('shop.booking_ahead_days_min'));
		}

		#[Computed]
		public function maxDate(): ?CarbonImmutable {
			return config('shop.booking_ahead_days_max')
				? CarbonImmutable::now()->startOfDay()->addDays(config('shop.booking_ahead_days_max'))
				: null;
		}

		#[Computed]
		public function disabledDates(): Collection {
			return DisabledDate::where('end', '>=', Date::now())
			                   ->where('active', true)
			                   ->get();
		}

		public function addToCart(CartRepository $repository): void {
			$this->validate();

			$cartItem = new CartItem($this->item, $this->start, $this->end, $this->amount, $this->comment);
			$repository->addCartItem($cartItem);

			session()->put('cart.lastStartDate', $this->start);
			session()->put('cart.lastEndDate', $this->end);

			$this->reset('start', 'end', 'amount', 'comment');

			$this->dispatch('item-added-to-cart');
			$this->dispatch('cart-changed');

			session()->flash(
				'cart.status.success', /** @lang Blade */
				'Artikel erfolgreich in den Warenkorb gelegt.<br><a href="' . route('shop.cart') . '" wire:navigate><i class="fa-solid fa-arrow-right me-1"></i>Zum Warenkorb</a>'
			);
			session()->flash('cart.status.raw');
		}
	}

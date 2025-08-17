<?php

	namespace App\Http\Components\Shop;

	use App\Models\DTOs\CartItem;
	use App\Models\DTOs\CheckoutData;
	use App\Repositories\CartRepository;
	use App\Traits\TrimWhitespaces;
	use Arr;
	use Illuminate\Contracts\View\View;
	use Illuminate\Validation\ValidationException;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Attributes\Validate;
	use Livewire\Component;

	/**
	 * @property-read array<CartItem> $items See {@see Checkout::items()} for getter.
	 */
	#[Title('Check-out: Daten angeben')]
	#[Layout('layouts.shop')]
	class Checkout extends Component {
		use TrimWhitespaces;

		private const array PREFILLABLE = ['forename', 'surname', 'email', 'mobile', 'rentalType', 'studying', 'legalname', 'street', 'number', 'zipcode', 'city', 'storePrefill'];

		#[Validate('required|string')]
		public string $forename;

		#[Validate('required|string')]
		public string $surname;

		#[Validate('required|string|email:strict')]
		public string $email;

		#[Validate('sometimes|nullable|string')]
		public ?string $mobile = null;

		#[Validate('required|string|in:personal,organisation')]
		public string $rentalType;

		#[Validate('required_if:rentalType,personal|nullable|string|in:hka,other,none')]
		public ?string $studying = null;

		#[Validate('required_if:rentalType,organisation|nullable|string')]
		public ?string $legalname = null;

		#[Validate('required|string')]
		public string $street;

		#[Validate('required|string')]
		public string $number;

		#[Validate('required|string')]
		public string $zipcode;

		#[Validate('required|string')]
		public string $city;

		#[Validate('required|string')]
		public string $eventName;

		#[Validate('sometimes|nullable|string')]
		public string $note = '';

		#[Validate('required')]
		public bool $revenue;

		public bool $storePrefill = false;

		public array $prefill = [];
		private bool $filled  = false;

		private CartRepository $cartRepository;

		public function boot(CartRepository $cartRepository): void {
			$this->cartRepository = $cartRepository;
		}

		public function mount(): void {
			if(!$this->filled) {
				if(session()->has('cart.checkout.formData')) {
					$this->fill(session('cart.checkout.formData'));
					$this->filled = true;
				}
			}
		}

		#[Computed]
		public function items(): array {
			return $this->cartRepository->getCartItems();
		}

		public function updatedPrefill(): void { // called when Alpine's entagle updates prefill from local storage
			if(!$this->filled && $this->prefill) {
				$this->fill(Arr::only($this->prefill, self::PREFILLABLE));
				$this->filled = true;
			}
		}

		public function render(): View|string {
			if($this->cartRepository->getItemValidationErrors()) {
				session()->flash('status.error', 'Die Bestellung konnte nicht abgeschickt werden, der Warenkorb ungültige Elemente enthält. Korrigiere diese, um fortzufahren.');
				$this->redirectRoute('shop.cart');
				return '<div></div>';
			}

			return view('components.shop.checkout');
		}

		public function storeData(): void {
			$this->validate();

			if($this->studying == 'none')
				throw ValidationException::withMessages([]); // message is hardcoded in frontend and always gets displayed whenever 'none' is checked

			if($this->rentalType != 'organisation') // reset legal name in case form was submitted previously with different rental type
				$this->legalname = null;

			session()->put('cart.checkout.formData', $this->all()); // only used for prefill
			$this->prefill = $this->storePrefill
				? $this->only(self::PREFILLABLE)
				: [];

			$cartHash = $this->cartRepository->getHash();

			if($this->revenue || $this->rentalType != 'personal' || $this->studying != 'hka') {
				$rate = 1;
			} else {
				$rate = $this->cartRepository->discountRate();
			}

			$checkoutData = CheckoutData::from([...$this->all(), 'rate' => $rate, 'cartHash' => $cartHash]);
			session()->put('cart.checkout.data', $checkoutData);

			$this->redirectRoute('shop.confirmation', ['checkoutHash' => $checkoutData->getHash()], navigate: true);
		}

	}

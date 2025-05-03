<?php

	namespace App\Http\Components\Shop;

	use App\Contracts\PriceCalculation;
	use App\Enums\OrderStatus;
	use App\Models\Content;
	use App\Models\Customer;
	use App\Models\DTOs\CartItem;
	use App\Models\DTOs\CheckoutData;
	use App\Models\Order;
	use App\Models\OrderItem;
	use App\Repositories\CartRepository;
	use App\Traits\HasCartItems;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\ValidationException;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	/**
	 * @property-read CheckoutData|null $checkoutData    See {@see Confirmation::checkoutData()} for getter.
	 * @property-read array<CartItem>   $cartItemsSorted See {@see Confirmation::cartItemsSorted()} for getter.
	 * @property-read float             $totalAmount     See {@see Confirmation::totalAmount()} for getter.
	 * @property-read float             $deposit         See {@see Confirmation::deposit()} for getter.
	 */
	#[Title('Check-out: Bestätigung')]
	#[Layout('layouts.shop')]
	class Confirmation extends Component {
		use HasCartItems;

		public bool $tos = false;

		#[Locked]
		public ?string             $checkoutHash = null;
		protected PriceCalculation $priceCalculator;
		protected CartRepository   $cartRepository;

		public function boot(PriceCalculation $priceCalculator, CartRepository $cartRepository): void {
			$this->priceCalculator = $priceCalculator;
			$this->cartRepository  = $cartRepository;
		}

		public function render() {
			if(!$this->validateCheckoutData())
				return '<div></div>';

			return view('components.shop.confirmation');
		}

		private function validateCheckoutData(bool $confirmed = false): bool {
			if(!$this->checkoutData || !$this->items || !$this->checkoutHash) {
				$this->redirectRoute('shop.checkout', navigate: true);
				return false;
			}

			if($this->cartRepository->getHash() != $this->checkoutData->cartHash) {
				session()->flash('status.info', 'Der Warenkorb wurde aktualisiert.');
				$this->redirectRoute('shop.checkout', navigate: true);
				return false;
			}

			if($this->checkoutData->getHash() != $this->checkoutHash) {
				session()->flash('status.info', 'Die Checkout-Daten wurden aktualisiert.');
				$this->redirectRoute('shop.confirmation', ['checkoutHash' => $this->checkoutData->getHash()], navigate: true);
				return false;
			}

			if($this->cartRepository->getItemValidationErrors()) {
				$message = ($confirmed ? 'Die Bestellung konnte nicht abgeschickt werden' : 'Mit dem Check-out konnte nicht fortgefahren werden') .
				           ', da der Warenkorb ungültige Elemente enthält. Korrigiere diese, um fortzufahren.';
				session()->flash('status.error', $message);
				$this->redirectRoute('shop.cart');
				return false;
			}

			return true;
		}

		#[Computed]
		public function checkoutData(): ?CheckoutData {
			return session('cart.checkout.data');
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
		public function deposit(): float {
			$deposit = 0;
			foreach($this->items as $cartItem)
				$deposit += $cartItem->item->deposit * $cartItem->amount;

			$depositSteps = config('shop.deposit_steps');
			rsort($depositSteps);

			// use maximum step that is smaller than calculated deposit
			foreach($depositSteps as $step) {
				if($step <= $deposit) {
					$deposit = $step;
					break;
				}
			}

			return $deposit;
		}

		public function checkout(): void {
			if(Content::fromName('checkout.tos')?->isNotEmpty() && !$this->tos) {
				throw ValidationException::withMessages(['tos' => 'Bitte stimme zu, wenn du fortfahren möchtest.']);
			}
			$this->resetValidation('tos');

			$order = DB::transaction(function (): ?Order {
				// prevent data races during checkout by locking orders
				DB::table('orders')->where('status', OrderStatus::PENDING)->lockForUpdate()->get();

				if(!$this->validateCheckoutData(confirmed: true)) // redirects on error
					return null;

				$customer = new Customer($this->checkoutData->all());
				$customer->save();

				$order = new Order();
				$order->customer()->associate($customer);
				$order->status     = OrderStatus::PENDING;
				$order->rate       = $this->checkoutData->rate;
				$order->event_name = $this->checkoutData->eventName;
				$order->note       = $this->checkoutData->note;
				$order->deposit    = $this->deposit;
				$order->save();

				foreach($this->items as $cartItem) {
					$orderItem = new OrderItem();
					$orderItem->order()->associate($order);
					$orderItem->item()->associate($cartItem->item);
					$orderItem->quantity       = $cartItem->amount;
					$orderItem->start          = $cartItem->start;
					$orderItem->end            = $cartItem->end;
					$orderItem->original_price = $this->priceCalculator->calculatePrice($cartItem->item, $cartItem->start, $cartItem->end) * $cartItem->amount;
					$orderItem->price          = $orderItem->original_price;
					$orderItem->comment        = $cartItem->comment;
					$orderItem->save();
				}

				return $order;
			});

			if($order) {
				$this->cartRepository->clearAllData();
				$this->dispatch('cart-changed');

				// TODO send confirmation email

				session()->put('order_success', $order->id); // using put and forget manually (instead of flash) as subcomponents might refresh prior to displaying success page, which leads to flash data being cleared prematurely.

				$this->redirectRoute('shop.success', navigate: true);
			}
		}
	}

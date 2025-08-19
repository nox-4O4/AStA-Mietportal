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
	use App\Notifications\NewOrderNotification;
	use App\Notifications\OrderReceiptConfirmation;
	use App\Repositories\CartRepository;
	use App\Traits\HasCartItems;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Notification;
	use Illuminate\Validation\ValidationException;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\Title;
	use Livewire\Component;
	use Throwable;

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

		public function render(): View|string {
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
			return $this->cartRepository->calculateDeposit();
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
					$orderItem->quantity = $cartItem->amount;
					$orderItem->start    = $cartItem->start;
					$orderItem->end      = $cartItem->end;
					$orderItem->comment  = $cartItem->comment;
					// price gets calculated by save event
					$orderItem->save();
				}

				return $order;
			});

			if($order) {
				$this->cartRepository->clearAllData();
				$this->dispatch('cart-changed');

				try {
					$order->customer->notify(new OrderReceiptConfirmation($order));
					if(config('shop.notification_address')) {
						Notification::route('mail', config('shop.notification_address'))
						            ->notify(new NewOrderNotification($order));
					}

					$mailSuccess = true;
				} catch(Throwable $t) {
					report($t);
					$mailSuccess = false;
				}

				session()->put('order_success', $order->id); // using put and forget manually (instead of flash) as subcomponents might refresh prior to displaying success page, which leads to flash data being cleared prematurely.
				session()->put('order_mail_success', $mailSuccess);

				$this->redirectRoute('shop.success', navigate: true);
			}
		}
	}

<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Contracts\PriceCalculation;
	use App\Models\DisabledDate;
	use App\Models\Item;
	use App\Models\Order;
	use App\Models\OrderItem;
	use App\Traits\TrimWhitespaces;
	use Carbon\CarbonImmutable;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	class OrderItemEditing extends Component {
		use TrimWhitespaces;

		#[Locked]
		public Order $order;

		#[Locked]
		public OrderItem $orderItem;

		public int     $item;
		public ?string $start = null;
		public ?string $end   = null;
		public int     $quantity;
		public float   $price;
		public string  $comment;

		protected PriceCalculation $priceCalculator;

		public function boot(PriceCalculation $priceCalculator): void {
			$this->priceCalculator = $priceCalculator;
		}

		public function loadOrderItem(?int $orderItemId): bool {
			if(!$orderItemId || !$orderItem = $this->order->orderItems()->where('id', $orderItemId)->first()) {
				return false;
			}

			$this->orderItem = $orderItem;
			$this->item      = $orderItem->item_id;
			$this->start     = $orderItem->start->format('Y-m-d');
			$this->end       = $orderItem->end->format('Y-m-d');
			$this->quantity  = $orderItem->quantity;
			$this->price     = $orderItem->price;
			$this->comment   = $orderItem->comment;

			$this->resetValidation();

			return true;
		}

		public function rules(): array {
			return [
				'item'     => [
					'required',
					'exists:items,id',
				],
				'start'    => [
					'required',
					'date',
				],
				'end'      => [
					'required',
					'date',
					'after_or_equal:start',
				],
				'quantity' => [
					'required',
					'integer',
				],
				'price'    => [
					'required',
					'numeric',
				],
				'comment'  => [
					'nullable',
					'string',
				]
			];
		}

		public function updateOrderItem(): void {
			$this->validate();

			if(!$this->quantity) {
				$this->orderItem->delete();
			} else {
				$this->orderItem->item_id  = $this->item;
				$this->orderItem->start    = $this->start;
				$this->orderItem->end      = $this->end;
				$this->orderItem->quantity = $this->quantity;
				$this->orderItem->price    = $this->price;
				$this->orderItem->comment  = $this->comment;
				$this->orderItem->save();
				$this->dispatch('refresh-order-meta');
			}

			$this->dispatch('refresh-data-table');
			$this->js('closeModal()');
		}

		#[Computed]
		public function items(): Collection {
			return Item::all()
			           ->sortBy( // cannot use database for sorting as we need natural sort
				           [
					           fn(Item $a, Item $b) => ($a->item_group_id !== null) <=> // makes sure items without group come first
					                                   ($b->item_group_id !== null),
					           'name', // uses mutated (composite) name
				           ], SORT_NATURAL
			           );
		}

		#[Computed]
		public function available(): int|true|null {
			if(!$this->start || !$this->end)
				return null;

			return Item::find($this->item)?->getMaximumAvailabilityInRange(
				start:            CarbonImmutable::make($this->start),
				end:              CarbonImmutable::make($this->end),
				excludeOrderItem: $this->orderItem->id, // exclude the order item that is currently being edited from availability calculation
			);
		}

		public function deleteItem(): void {
			$this->orderItem->delete();
			$this->dispatch('refresh-order-meta');
			$this->dispatch('refresh-data-table');
			$this->js('closeModal()');
		}

		public function render() {
			return view('components.dashboard.orders.order-item-editing');
		}

		#[Computed]
		public function disabledDates(): Collection {
			if(!$this->start || !$this->end)
				return Collection::empty();

			return DisabledDate::getOverlappingRanges(CarbonImmutable::make($this->start), CarbonImmutable::make($this->end));
		}

		#[Computed]
		public function singleItemAmount(): ?float {
			if(!$this->start || !$this->end || !$item = Item::find($this->item))
				return null;

			return $this->priceCalculator->calculatePrice($item, CarbonImmutable::make($this->start), CarbonImmutable::make($this->end));
		}

	}

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
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	class OrderItemEditing extends Component {
		use TrimWhitespaces;

		#[Locked]
		public Order $order;

		#[Locked]
		public ?int $orderItemId = null;

		public ?int    $itemId  = null;
		public ?string $start   = null;
		public ?string $end     = null;
		public int     $quantity;
		public float   $price;
		public string  $comment = "";

		protected PriceCalculation $priceCalculator;

		public function boot(PriceCalculation $priceCalculator): void {
			$this->priceCalculator = $priceCalculator;
		}

		public function loadOrderItem(int $orderItemId): bool {
			if(!$orderItemId || !$orderItem = $this->order->orderItems()->where('id', $orderItemId)->first()) {
				return false;
			}

			$this->orderItemId = $orderItemId;
			$this->itemId      = $orderItem->item_id;
			$this->start       = $orderItem->start->format('Y-m-d');
			$this->end         = $orderItem->end->format('Y-m-d');
			$this->quantity    = $orderItem->quantity;
			$this->price       = $orderItem->price;
			$this->comment     = $orderItem->comment;

			$this->resetValidation();

			return true;
		}

		public function resetOrderItem(): void {
			$this->resetExcept('order');

			// prefill common values for when creating new order items
			$this->start    = session()->get('editOrderItem.lastStartDate', $this->order->common_start?->format('Y-m-d'));
			$this->end      = session()->get('editOrderItem.lastEndDate', $this->order->common_end?->format('Y-m-d'));
			$this->quantity = 1;

			$this->resetValidation();
		}

		public function rules(): array {
			return [
				'itemId'   => [
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
					Rule::when(!$this->orderItemId, 'gte:1'),
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

		public function saveOrderItem(): void {
			$this->validate();

			if($this->orderItemId && !$this->quantity) { // remove an existing order item that got its quantity set to zero
				OrderItem::find($this->orderItemId)?->delete();

			} else {
				if($this->orderItemId) {
					$orderItem = OrderItem::find($this->orderItemId);

					// hash for the datatable component won't change when only editing an item, so manually dispatch refresh event
					$this->dispatch('order-items-changed');

				} else { // Create a new order item. This also changes collection hash, leading to datatable refresh.
					$orderItem = new OrderItem();
					$orderItem->order()->associate($this->order);
					$resetAfterSave = true;
				}

				$orderItem->item_id  = $this->itemId;
				$orderItem->start    = $this->start;
				$orderItem->end      = $this->end;
				$orderItem->quantity = $this->quantity;
				$orderItem->price    = $this->price;
				$orderItem->comment  = $this->comment;
				$orderItem->save();

				session()->put('editOrderItem.lastStartDate', $this->start);
				session()->put('editOrderItem.lastEndDate', $this->end);

				// when creating a new item, reset form after saving so old content won't flash when re-opening form
				if(isset($resetAfterSave))
					$this->resetOrderItem();

				$this->dispatch('order-meta-changed');
			}

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
			if(!$this->start || !$this->end || !$this->itemId)
				return null;

			return Item::find($this->itemId)?->getMaximumAvailabilityInRange(
				start:            CarbonImmutable::make($this->start),
				end:              CarbonImmutable::make($this->end),
				excludeOrderItem: $this->orderItemId, // exclude the order item that is currently being edited from availability calculation
			);
		}

		public function deleteItem(): void {
			if($this->orderItemId) {
				OrderItem::find($this->orderItemId)?->delete();

				// no need for order-items-changed event as wire:key of the datatable component will change due to different hash
				$this->dispatch('order-meta-changed');
			}

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
			if(!$this->start || !$this->end || !$this->itemId || !$item = Item::find($this->itemId))
				return null;

			return $this->priceCalculator->calculatePrice($item, CarbonImmutable::make($this->start), CarbonImmutable::make($this->end));
		}

	}

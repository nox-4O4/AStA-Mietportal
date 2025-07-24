<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Http\Forms\EditOrderForm;
	use App\Models\Order;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class OrderDetailView extends Component {
		use TrimWhitespaces;

		#[Locked]
		public Order $order;

		public EditOrderForm $editOrderForm;

		public function mount(): void {
			$this->loadInitialData();
		}

		public function cancel(): void {
			$this->loadInitialData();
		}

		public function render(): View {
			return view('components.dashboard.orders.order-detail-view')
				->title("Bestellung #{$this->order->id}");
		}

		public function updateOrder(): void {
			$this->editOrderForm->validate();

			$this->order->customer->fill($this->editOrderForm->all());
			$this->order->customer->save();

			$this->order->status     = $this->editOrderForm->status;
			$this->order->rate       = 1 - $this->editOrderForm->discount / 100;
			$this->order->event_name = $this->editOrderForm->eventName;
			$this->order->note       = $this->editOrderForm->note;
			$this->order->deposit    = $this->editOrderForm->deposit;
			$this->order->save();

			// only adjust items when dates were changed
			if($this->editOrderForm->start && $this->editOrderForm->end && (!$this->order->hasSinglePeriod ||
			                                                                $this->order->common_start->notEqualTo($this->editOrderForm->start) ||
			                                                                $this->order->common_end->notEqualTo($this->editOrderForm->end))
			) {
				foreach($this->order->orderItems as $orderItem) {
					$orderItem->start = $this->editOrderForm->start;
					$orderItem->end   = $this->editOrderForm->end;
					if($this->editOrderForm->recalculatePrice)
						unset($orderItem->price); // this makes event handler re-apply calculated price
					$orderItem->update();
				}

				$this->dispatch('refresh-data-table');
			}

			$this->editOrderForm->recalculatePrice = true; // reset to initial state

			// hide update form on success
			$this->js('edit=false');
		}

		private function loadInitialData(): void {
			$this->editOrderForm->loadOrder($this->order);
		}
	}

<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Http\Forms\EditOrderForm;
	use App\Models\Customer;
	use App\Models\Order;
	use App\Traits\TrimWhitespaces;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Facades\DB;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\On;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[On('order-meta-changed')]
	#[Layout('layouts.dashboard')]
	#[Title('Bestellung anlegen')]
	class OrderCreate extends Component {
		use TrimWhitespaces;

		public EditOrderForm $editOrderForm;

		public function render(): View {
			return view('components.dashboard.orders.order-create');
		}

		public function createOrder(): void {
			$this->editOrderForm->validate();

			$order = DB::transaction(function (): Order {
				$customer = new Customer($this->editOrderForm->all());
				$customer->save();

				$order = new Order();
				$order->customer()->associate($customer);

				$order->status     = $this->editOrderForm->status;
				$order->rate       = 1 - $this->editOrderForm->discount / 100;
				$order->event_name = $this->editOrderForm->eventName;
				$order->note       = $this->editOrderForm->note;
				$order->deposit    = $this->editOrderForm->deposit;
				$order->save();

				return $order;
			});

			session()->flash('status.success', "Bestellung #$order->id erfolgreich angelegt.");
			$this->redirectRoute('dashboard.orders.view', ['order' => $order->id], navigate: true);
		}
	}

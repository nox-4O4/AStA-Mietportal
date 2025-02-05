<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Models\Order;
	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class OrderDetailView extends Component {

		#[Locked]
		public Order $order;

		public function render(): View {
			return view('components.dashboard.orders.order-detail-view')
				->title("Bestellung #{$this->order->id}");
		}
	}

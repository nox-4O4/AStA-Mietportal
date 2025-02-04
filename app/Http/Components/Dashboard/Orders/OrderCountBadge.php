<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Enums\OrderStatus;
	use App\Models\Order;
	use Livewire\Attributes\Computed;
	use Livewire\Component;

	class OrderCountBadge extends Component {

		#[Computed]
		public function count() {
			return Order::where('status', OrderStatus::PENDING)->count();
		}
	}

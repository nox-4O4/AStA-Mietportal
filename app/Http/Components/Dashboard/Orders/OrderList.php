<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Enums\OrderStatus;
	use App\Models\Order;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Collection;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Lazy;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Lazy]
	#[Title('Bestellungen')]
	#[Layout('layouts.dashboard')]
	class OrderList extends Component {

		#[Computed]
		public function orders(): Collection {
			return Order::orderByDesc('created_at')->with(['customer', 'orderItems'])->get();
		}

		public function countOrders(?OrderStatus $status = null): int {
			static $cache = [];

			return $cache[$status->value ?? 0] ??= $status
				? Order::where('status', $status)->count()
				: Order::count();
		}

		public function render(): View {
			return view('components.dashboard.orders.order-list');
		}

		public function placeholder(array $params = []) {
			// we want to preserve flashed session data when showing the placeholder, so reflash them
			session()->reflash();

			return view('components.dashboard.orders.order-list-placeholder');
		}
	}

<?php

	namespace App\Events;

	use App\Models\Order;

	interface OrderEvent {

		public function __construct(Order $order);
	}

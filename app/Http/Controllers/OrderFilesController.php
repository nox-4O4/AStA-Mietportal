<?php

	namespace App\Http\Controllers;

	use App\Models\Order;
	use Symfony\Component\HttpFoundation\HeaderUtils;
	use Symfony\Component\HttpFoundation\Response;

	class OrderFilesController extends Controller {
		public function getOrderConfirmation(Order $order): Response {
			return response()->streamDownload(
				fn() => print $order->orderSummaryPDF,
				"Bestellübersicht #$order->id.pdf",
				['Content-Type' => 'application/pdf'],
				HeaderUtils::DISPOSITION_INLINE,
			);
		}
	}

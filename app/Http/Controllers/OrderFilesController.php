<?php

	namespace App\Http\Controllers;

	use App\Models\Invoice;
	use App\Models\Order;
	use Illuminate\Support\Facades\DB;
	use Symfony\Component\HttpFoundation\HeaderUtils;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class OrderFilesController extends Controller {
		public function getOrderConfirmation(Order $order): Response {
			return response()->streamDownload(
				fn() => print $order->orderSummaryPDF,
				"Bestellübersicht #$order->id.pdf",
				['Content-Type' => 'application/pdf'],
				HeaderUtils::DISPOSITION_INLINE,
			);
		}

		public function getOrderContract(Order $order): Response {
			return response()->streamDownload(
				fn() => print $order->orderContractPDF,
				"Mietvertrag #$order->id.pdf",
				['Content-Type' => 'application/pdf'],
				HeaderUtils::DISPOSITION_INLINE,
			);
		}

		public function getOrderInvoice(Order $order, Invoice $invoice): Response {
			if(!$invoice->order || $invoice->order->id != $order->id)
				throw new NotFoundHttpException();

			return response()
				->download($invoice->filePath, $invoice->fileName, disposition: HeaderUtils::DISPOSITION_INLINE)
				->setPrivate();
		}

		public function getOrderInvoiceCancellation(Order $order, Invoice $invoice): Response {
			if(!$invoice->order || $invoice->order->id != $order->id || !$invoice->cancelled)
				throw new NotFoundHttpException();

			return response()
				->download($invoice->cancellationFilePath, $invoice->cancellationFileName, disposition: HeaderUtils::DISPOSITION_INLINE)
				->setPrivate();
		}

		public function getOrderInvoicePreview(Order $order): Response {
			return DB::transaction(function () use ($order) {
				if($order->status->orderClosed()) {
					session()->flash('status.error', 'Die Bestellung ist bereits ' . strtolower($order->status->getShortName()) . '. Daher kann keine neue Rechnungsvorschau mehr erzeugt werden.');
					return redirect()->route('dashboard.orders.view', ['order' => $order->id]);
				}

				if($order->currentInvoice) {
					session()->flash('status.error', 'Die Bestellung verfügt bereits über eine aktuelle Rechnung. Daher kann keine neue Rechnungsvorschau erzeugt werden.');
					return redirect()->route('dashboard.orders.view', ['order' => $order->id]);
				}

				if($order->orderItems->isEmpty()) {
					session()->flash('status.error', 'Die Bestellung verfügt über keine Artikel. Daher kann keine Rechnungsvorschau erzeugt werden.');
					return redirect()->route('dashboard.orders.view', ['order' => $order->id]);
				}

				return response()->streamDownload(
					fn() => print Invoice::GeneratePreviewFor($order),
					"Rechnungsvorschau #$order->id.pdf",
					['Content-Type' => 'application/pdf'],
					HeaderUtils::DISPOSITION_INLINE,
				);
			});
		}
	}

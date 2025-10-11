<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Enums\OrderStatus;
	use App\Events\InvoiceDataChanged;
	use App\Http\Forms\EditOrderForm;
	use App\Models\Comment;
	use App\Models\Order;
	use App\Notifications\OrderSummary;
	use App\Traits\TrimWhitespaces;
	use Auth;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Facades\DB;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\On;
	use Livewire\Component;

	#[Layout('layouts.dashboard')]
	class OrderDetailView extends Component {
		use TrimWhitespaces;

		#[Locked]
		public Order $order;

		public EditOrderForm $editOrderForm;

		public ?string $newComment  = null;
		public bool    $allComments = false;

		#[On('order-meta-changed')] // When the event occurs the component gets re-rendered. By having loadInitialData() executed the form fields are also updated.
		public function mount(): void {
			$this->editOrderForm->includeClosedOrderStatus = true;
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
			DB::transaction(function () {
				// prevent simultaneous updates by locking value
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if($this->order->status->orderClosed()) {
					// It should not be possible to perform updateOrder() when the order is currently closed.
					// This may still happen when the order was left open in a different tab, and the order was closed in the meantime.
					// In this case we close the edit form and invoke a change event, which leads to the item list refreshing (and thus hiding the edit buttons).
					// The OrderDetailView component refreshes automatically and will display the warning message.

					$this->dispatch('order-items-changed');
					$this->js('edit=false');
					return;
				}

				$this->editOrderForm->validate();

				$this->order->customer->fill($this->editOrderForm->all());
				$this->order->customer->save();

				// only perform immediate order status updates when no additional actions have to be performed
				if(
					!OrderStatus::from($this->editOrderForm->status)->orderClosed() ||
					$this->editOrderForm->status == OrderStatus::CANCELLED->value && $this->order->canBeCancelled() ||
					$this->editOrderForm->status == OrderStatus::COMPLETED->value && $this->order->canBeCompleted()
				) {
					$this->order->status = $this->editOrderForm->status;
				}

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
					// TODO display disabledDates and item amount validation in FE when changing dates. Should also consider changed dates
					// (e.g., item amount = 2, two order items exist with differing dates and each amount = 2, it's okay if dates don't overlap, with changed dates total amount would be 4)

					foreach($this->order->orderItems as $orderItem) {
						$orderItem->start = $this->editOrderForm->start;
						$orderItem->end   = $this->editOrderForm->end;
						if($this->editOrderForm->recalculatePrice)
							unset($orderItem->price); // this makes event handler re-apply calculated price
						$orderItem->update();
					}

					$this->dispatch('order-items-changed');
				}

				$this->order->dispatchQueuedEvents();

				// Process additional constraints when changing order status to cancelled or completed, see Rechnungsverwaltung.md for details.
				if($this->order->status->value != $this->editOrderForm->status) {
					if($this->editOrderForm->status == OrderStatus::CANCELLED->value) {
						// cancellation conditions are not touched by order data change, so we know the order can currently not be cancelled without further action
						if($this->editOrderForm->automaticInvoiceUpdates) {
							// do whatever is necessary to get this order cancelled
							$cancelledInvoicesToNotify = [];
							foreach($this->order->invoices as $invoice) {
								if(!$invoice->cancelled)
									$invoice->cancel();

								if($invoice->notified && !$invoice->cancellation_notified)
									$cancelledInvoicesToNotify[] = $invoice;
							}

							if($cancelledInvoicesToNotify)
								$this->order->sendInvoiceNotification([], $cancelledInvoicesToNotify);

							// now we can update the order status
							$this->order->status = OrderStatus::CANCELLED;
							InvoiceDataChanged::dispatch($this->order); // update flag directly without queuing event
							$this->order->save();

							session()->flash('status.success', $cancelledInvoicesToNotify
								? "Die Bestellung und ihre Rechnung wurden storniert. Es wurde eine Benachrichtigung über die Rechnungsstornierung an {$this->order->customer->email} gesendet."
								: 'Die Bestellung und ihre Rechnung wurden storniert.'
							);
						} else {
							session()->flash('status.error', 'Die Bestellung kann nicht storniert werden, solange sie über eine nicht-stornierte Rechnung verfügt oder der Kunde zu einer bereits versendeten und mittlerweile stornierten Rechnung kein Rechnungsstorno erhalten hat.');
						}
					} else if($this->editOrderForm->status == OrderStatus::COMPLETED->value) {
						// updating the order might have changed completion conditions, so reevaluate (invoiceRequired flag might have changed)
						if($this->order->canBeCompleted()) {
							$this->order->status = OrderStatus::COMPLETED;
							$this->order->save();
						} else {
							if($this->editOrderForm->automaticInvoiceUpdates) {
								// do whatever is necessary to get this order completed
								$invoiceCreated = false;
								if($this->order->invoice_required) {
									$invoiceCreated = $this->order->createInvoice();
									$this->order->dispatchQueuedEvents();
								}

								$cancelledInvoicesToNotify = $invoicesToNotify = [];
								foreach($this->order->invoices as $invoice)
									if($invoice->notified && $invoice->cancelled && !$invoice->cancellation_notified)
										$cancelledInvoicesToNotify[] = $invoice;

								// We also send the current invoice when there is a cancelled invoice that the customer is being notified about, regardless of the current invoice amount.
								// While this is not strictly required if the current invoices has a zero amount, it improves UX. The customer gets an e-mail anyway, this way they will know that the old invoice got replaced with an zero-amount invoice (as opposed to just being cancelled, e.g. due to an error).
								if(
									($currentInvoice = $this->order->currentInvoice) &&
									!$currentInvoice->notified &&
									($currentInvoice->total_amount != 0 || $cancelledInvoicesToNotify)
								) {
									$invoicesToNotify[] = $currentInvoice;
								}

								if($cancelledInvoicesToNotify || $invoicesToNotify)
									$this->order->sendInvoiceNotification($invoicesToNotify, $cancelledInvoicesToNotify);

								$this->order->status = OrderStatus::COMPLETED;
								$this->order->save();

								if($cancelledInvoicesToNotify || $invoicesToNotify) {
									session()->flash('status.success', $invoiceCreated
										? "Die Bestellung wurde aktualisiert. Es wurde eine Rechnung erstellt und an {$this->order->customer->email} gesendet."
										: "Die Bestellung wurde aktualisiert. Es wurde eine Rechnungs-E-Mail an {$this->order->customer->email} gesendet."
									);
								} else {
									session()->flash('status.success', $invoiceCreated
										? "Die Bestellung wurde aktualisiert. Es wurde eine aktuelle Rechnung erstellt."
										: 'Die Bestellung wurde aktualisiert.' // this case cannot not occur as canBeCompleted() whould have returned true when no action is required
									);
								}
							} else {
								session()->flash('status.error', 'Die Bestellung kann nicht abgeschlossen werden, solange der Kunde keine aktuelle Rechnung erhalten hat oder er zu einer bereits versendeten und mittlerweile stornierten Rechnung kein Rechnungsstorno erhalten hat.');
							}
						}
					}
				}

				if($this->order->status->orderClosed())
					$this->dispatch('order-items-changed'); // hides edit buttons in table rows

				$this->editOrderForm->loadOrder($this->order); // reset checkboxes to initial state. Also resets status in case change was prevented.

				// hide update form on success
				$this->js('edit=false');
			});
		}

		public function cancelComment(): void {
			$this->newComment = null;
			$this->resetValidation('newComment');
		}

		public function addComment(): void {
			$this->validate(['newComment' => ['required', 'string']]);

			$comment           = new Comment();
			$comment->comment  = $this->newComment;
			$comment->order_id = $this->order->id;
			$comment->user()->associate(Auth::user());
			$comment->save();

			$this->newComment = null;

			$this->js('addComment=false');
		}

		public function showAllComments(): void {
			$this->allComments = true;
		}

		public function deleteComment(int $id): void {
			$comment = Comment::find($id);
			if($comment->user->id == Auth::user()->id && $comment->order->id == $this->order->id)
				$comment->delete();
		}

		private function loadInitialData(): void {
			$this->editOrderForm->loadOrder($this->order);
		}

		public function deleteItem(int $orderItemId): void {
			DB::transaction(function () use ($orderItemId) {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if($this->order->status->orderClosed()) {
					$this->dispatch('order-items-changed');
					return;
				}

				if(!$orderItem = $this->order->orderItems()->where('id', $orderItemId)->first())
					return;

				$autoAdjustDeposit = $this->order->deposit == $this->order->calculatedDeposit;

				$orderItem->delete();
				$this->order->refresh(); // resets cached orderItems collection as well as cached calculatedDeposit value

				if($autoAdjustDeposit) {
					$this->order->deposit = $this->order->calculatedDeposit;
					$this->order->save();
				}

				$this->order->dispatchQueuedEvents();

				$this->loadInitialData(); // updates common start/end prefilled values and deposit in case they changed due to item deletion

				// no need for order-items-changed event as wire:key of the datatable component will change due to different hash
			});
		}

		public function recalculateItemPrices(): void {
			DB::transaction(function () {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if(!$this->order->status->orderClosed()) {
					foreach($this->order->orderItems as $orderItem) {
						unset($orderItem->price);
						$orderItem->update(); // saving event calculates and sets price
					}
				}

				$this->dispatch('order-items-changed');
			});
		}

		public function openOrder(): void {
			DB::transaction(function () {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if($this->order->status->orderClosed()) {
					$this->order->status = OrderStatus::PROCESSING;
					$this->order->save();
					$this->order->dispatchQueuedEvents(); // changing order status might change invoice requirement
				}

				$this->editOrderForm->loadOrder($this->order);
				$this->dispatch('order-items-changed');

				// When opening the edit item modal while the order is cancelled, an error message is displayed. After the order is repoened, the error message is still visible until the modal reloads.
				// By dispatching the order-reopened event, the modal refreshes as soon as the order is opened again, so the error message does not flash.
				$this->dispatch('order-reopened');
			});
		}

		public function deleteOrder(): void {
			if(DB::transaction(function () {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if(!$this->order->canBeCancelled()) {
					session()->flash(
						'status.error',
						'Die Bestellung kann nicht gelöscht werden, solange sie nicht-stornierte Rechnungen oder Rechnungen mit ausstehenden Benachrichtigungen enthält.' .
						($this->order->status != OrderStatus::CANCELLED
							? ' Storniere erst die Bestellung, um sie löschen zu können.'
							: '')
					);

					return false;
				}

				if($this->order->invoices->isEmpty())
					$this->order->customer->delete();

				$this->order->delete();

				return true;
			})) {
				session()->flash('status.success', "Bestellung #{$this->order->id} erfolgreich gelöscht.");
				$this->redirectRoute('dashboard.orders.list', navigate: true);
			}
		}

		public function sendOrderSummary(): void {
			DB::transaction(function () {
				if($this->order->status == OrderStatus::CANCELLED) {
					session()->flash('status.error', 'Zu einer stornierten Bestellung kann keine Bestellübersicht versendet werden.');
					$this->dispatch('order-items-changed'); // page might be outdated if order was changed in diferent tab / session, so reload list to remove edit controls if they are still displayed
				} else {
					$this->order->customer->notify(new OrderSummary($this->order));

					session()->flash('status.success', "Es wurde eine aktuelle Bestellübersicht an {$this->order->customer->email} verschickt.");
				}
			});
		}

		public function createInvoice(): void {
			DB::transaction(function () {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if($this->order->status->orderClosed()) {
					session()->flash('status.error', 'Die Bestellung ist bereits ' . strtolower($this->order->status->getShortName()) . '. Daher können keine Rechnungen mehr erstellt werden.');
					return;
				}

				if($this->order->createInvoice()) {
					session()->flash('status.success', "Es wurde die Rechnung {$this->order->currentInvoice->name} erstellt.");
				}

				$this->order->dispatchQueuedEvents();
			});
		}

		public function sendNotification(): void {
			DB::transaction(function () {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				// first check if any notifications are missing
				$newInvoices = $cancelledInvoices = [];
				foreach($this->order->invoices->reverse() as $invoice) { // reverse order so that cancelled invoices are found first
					if($invoice->cancelled) {
						if($invoice->notified && !$invoice->cancellation_notified)
							$cancelledInvoices[] = $invoice;
					} else {
						// We also send the invoice when there is a cancelled invoice that the customer is being notified about, regardless of the amount of the non-cancelled invoice.
						// While this is not strictly required if the current invoices has a zero amount, it improves UX. The customer gets an e-mail anyway, this way they will know that the old invoice got replaced with an zero-amount invoice (as opposed to just being cancelled, e.g. due to an error).
						if(!$invoice->notified && ($invoice->total_amount != 0 || $cancelledInvoices))
							$newInvoices[] = $invoice;
					}
				}

				if(!$cancelledInvoices && !$newInvoices && ($currentInvoice = $this->order->currentInvoice)) {
					// no notifications are missing, notify about current invoice

					if($this->order->status == OrderStatus::CANCELLED) {
						session()->flash('status.error', 'Die Bestellung ist storniert. Daher können keine Rechnungen mehr versendet werden.');
						return;
					}

					$newInvoices[] = $currentInvoice;
				} else {
					// have most recent invoice appear first in e-mail
					$newInvoices       = array_reverse($newInvoices);
					$cancelledInvoices = array_reverse($cancelledInvoices);
				}

				if($newInvoices || $cancelledInvoices) {
					$this->order->sendInvoiceNotification($newInvoices, $cancelledInvoices);
					session()->flash('status.success', "Es wurde eine Rechnungs-E-Mail an {$this->order->customer->email} gesendet.");

				} else {
					session()->flash('status.info', 'Es gibt keine Rechnungen zu versenden.');
				}
			});
		}

		public function cancelInvoice(int $invoiceId): void {
			DB::transaction(function () use ($invoiceId) {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				if(!$invoice = $this->order->invoices()->where('id', $invoiceId)->where('cancelled', false)->first())
					return;

				if($this->order->status == OrderStatus::COMPLETED) {
					session()->flash('status.error', 'Die Bestellung ist bereits abgeschlossen. Daher können keine Rechnungen mehr storniert werden.');
					return;
				}

				$invoice->cancel();
				$this->order->dispatchQueuedEvents();
			});
		}

		public function cancelAllInvoices(): void {
			DB::transaction(function () {
				$this->order = Order::whereKey($this->order)->lockForUpdate()->first();

				$displayErrorMessage = $this->order->status == OrderStatus::COMPLETED;

				foreach($this->order->invoices as $invoice) { // usually there is at most one non-cancelled invoice
					if(!$invoice->cancelled) {
						if($displayErrorMessage) {
							// only display error message when a change would have occured otherwise
							session()->flash('status.error', 'Die Bestellung ist bereits abgeschlossen. Daher können keine Rechnungen mehr storniert werden.');
							return;
						}

						$invoice->cancel();
					}
				}

				$this->order->dispatchQueuedEvents();
			});
		}
	}

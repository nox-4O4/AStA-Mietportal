<?php

	namespace App\Http\Components\Dashboard\Orders;

	use App\Http\Forms\EditOrderForm;
	use App\Models\Comment;
	use App\Models\Order;
	use App\Traits\TrimWhitespaces;
	use Auth;
	use Illuminate\Contracts\View\View;
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

			$this->editOrderForm->recalculatePrice = true; // reset to initial state

			// hide update form on success
			$this->js('edit=false');
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

		public function removeItem(int $orderItemId): void {
			if(!$orderItem = $this->order->orderItems()->where('id', $orderItemId)->first())
				return;

			$autoAdjustDeposit = $this->order->deposit == $this->order->calculatedDeposit;

			$orderItem->delete();
			$this->order->refresh(); // resets cached orderItems collection as well as cached calculatedDeposit value

			if($autoAdjustDeposit) {
				$this->order->deposit = $this->order->calculatedDeposit;
				$this->order->save();
			}

			$this->loadInitialData(); // updates common start/end prefilled values and deposit in case they changed due to item deletion

			// no need for order-items-changed event as wire:key of the datatable component will change due to different hash
		}

		public function recalculateItemPrices(): void {
			foreach($this->order->orderItems as $orderItem) {
				unset($orderItem->price);
				$orderItem->update(); // saving event calculates and sets price
			}
			$this->dispatch('order-items-changed');
		}

		public function deleteOrder(): void {
			// TODO prevent deletion for non-admins when invoices exist

			$this->order->delete();
			session()->flash('status.success', "Bestellung #{$this->order->id} erfolgreich gelöscht.");

			$this->redirectRoute('dashboard.orders.list', navigate: true);
		}
	}

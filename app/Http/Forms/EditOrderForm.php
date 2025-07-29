<?php

	namespace App\Http\Forms;

	use App\Enums\OrderStatus;
	use App\Models\Order;
	use Illuminate\Validation\Rule;
	use Livewire\Attributes\Validate;
	use Livewire\Form;

	class EditOrderForm extends Form {

		#[Validate('required|string')]
		public string $forename;

		#[Validate('required|string')]
		public string $surname;

		#[Validate('nullable|string')]
		public ?string $legalname;

		#[Validate('nullable|required_with:city|required_with:number|string')]
		public ?string $street;

		#[Validate('nullable|string')]
		public ?string $number;

		#[Validate('nullable|required_with:city|string')]
		public ?string $zipcode;

		#[Validate('nullable|required_with:zipcode|required_with:street|string')]
		public ?string $city;

		#[Validate('required|string|email:strict')]
		public string $email;

		#[Validate('nullable|string')]
		public ?string $mobile;

		#[Validate]
		public string $status;

		#[Validate('required|int|between:0,100')]
		public int $discount;

		#[Validate('required|string')]
		public string $eventName;

		#[Validate('nullable|string')]
		public ?string $note;

		#[Validate('required|int|gte:0')]
		public float $deposit;

		#[Validate('nullable|required_with:end|date')]
		public ?string $start = null;

		#[Validate('nullable|required_with:start|date|after_or_equal:start')]
		public ?string $end = null;

		public bool $recalculatePrice = true;

		public function rules(): array {
			return [
				'status' => [
					'required',
					Rule::enum(OrderStatus::class),
				],
			];
		}

		public function loadOrder(Order $order): void {
			$this->fill($order);
			$this->fill($order->customer);

			$this->eventName = $order->event_name;
			$this->discount  = (1 - $order->rate) * 100;

			if($order->hasSinglePeriod) {
				$this->start = $order->common_start->format('Y-m-d');
				$this->end   = $order->common_end->format('Y-m-d');
			} else {
				$this->start = null;
				$this->end   = null;
			}

			$this->recalculatePrice = true;

			$this->resetValidation();
		}

	}

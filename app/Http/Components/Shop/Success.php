<?php

	namespace App\Http\Components\Shop;

	use Illuminate\Contracts\View\View;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Title;
	use Livewire\Component;

	#[Title('Bestellung erfolgreich')]
	#[Layout('layouts.shop')]
	class Success extends Component {

		public function render(): View|string {
			if(!session()->has('order_success')) {
				$this->redirectRoute('shop');
				return '<div></div>';
			}

			return view('components.shop.success', ['order_id' => session()->pull('order_success')]);
		}
	}

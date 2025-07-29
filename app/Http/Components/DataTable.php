<?php

	namespace App\Http\Components;

	use Illuminate\Support\Enumerable;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\Str;
	use Illuminate\View\View;
	use Livewire\Attributes\Locked;
	use Livewire\Attributes\On;
	use Livewire\Component;

	#[On('order-items-changed')]
	class DataTable extends Component {

		public Enumerable $elements;
		public string     $itemComponent;
		public string     $id;
		public string     $class;

		#[Locked]
		public array $elementAttributes;
		#[Locked]
		public array $itemComponentData;

		public function mount(Enumerable $elements,
		                      string     $itemComponent,
		                      string     $id = '',
		                      string     $class = '',
		                      array      $elementAttributes = [],
		                      array      $itemComponentData = [],
		): void {
			if(!strlen(trim($id)))
				$id = 'dt_' . Str::slug(Route::getCurrentRoute()->getName(), '_');

			$this->fill([
				            'elements'          => $elements,
				            'itemComponent'     => $itemComponent,
				            'id'                => $id,
				            'class'             => $class,
				            'elementAttributes' => $elementAttributes,
				            'itemComponentData' => $itemComponentData,
			            ]);
		}

		public function render(): View {
			return view('components.data-table');
		}
	}

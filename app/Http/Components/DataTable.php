<?php

	namespace App\Http\Components;

	use Illuminate\Support\Enumerable;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\Str;
	use Livewire\Component;

	class DataTable extends Component {

		public Enumerable $elements;
		public string     $itemComponent;
		public string     $id;
		public string     $class;

		public function mount(Enumerable $elements,
		                      string     $itemComponent,
		                      string     $id = '',
		                      string     $class = '',
		): void {
			if(!strlen(trim($id)))
				$id = 'dt_' . Str::slug(Route::getCurrentRoute()->getName(), '_');

			$this->fill([
				            'elements'      => $elements,
				            'itemComponent' => $itemComponent,
				            'id'            => $id,
				            'class'         => $class,
			            ]);
		}
	}

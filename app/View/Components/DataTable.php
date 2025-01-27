<?php

	namespace App\View\Components;

	use Closure;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Enumerable;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\Str;
	use Illuminate\View\Component;

	class DataTable extends Component {
		/**
		 * Create a new component instance.
		 */
		public function __construct(public array|Enumerable $elements,
		                            public string           $itemComponent,
		                            public string           $class = '',
		                            public string           $id = '',

		) {
			if(!strlen(trim($id)))
				$this->id = 'dt_' . Str::slug(Route::getCurrentRoute()->getName(), '_');
		}

		/**
		 * Get the view / contents that represent the component.
		 */
		public function render(): View|Closure|string {
			return view('components.data-table');
		}
	}

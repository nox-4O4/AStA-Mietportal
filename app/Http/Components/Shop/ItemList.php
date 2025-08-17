<?php

	namespace App\Http\Components\Shop;

	use App\Models\DTOs\ItemListEntry;
	use App\Models\Item;
	use App\Models\ItemGroup;
	use App\Util\Helper;
	use Illuminate\Contracts\View\View;
	use Illuminate\Support\Arr;
	use Illuminate\Support\Facades\Gate;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Attributes\Url;
	use Livewire\Component;
	use Transliterator;

	/**
	 * @property-read array<ItemListEntry> $items See {@see ItemList::items()} for property getter
	 */
	#[Layout('layouts.shop')]
	class ItemList extends Component {

		#[Url(as: 'suche', except: '')]
		public string $search = '';

		#[Url(except: false)]
		public bool $searchDescription = false;

		#[Computed]
		public function items(): array {
			$result = Item::getDisplayItemElements();
			$result = array_filter($result, fn(ItemListEntry $element): bool => Gate::allows('view', $element));

			if($this->search !== '')
				$result = self::filterValues($result);

			return $result;
		}

		public function render(): View {
			if($this->search !== '' && count($this->items) == 1) {
				$item = Arr::first($this->items);

				// when our search result contains only a single item group check if the search query is specific enough to identify a single item of that group.
				// If it is specific enough we redirect the user directly to that item. Otherwise, redirect user to item group.
				if($item->grouped) {
					$items  = array_map(
						fn(Item $item) => new ItemListEntry($item->id, $item->name, null, false, 0, $item->visible, $item->available), // only 'id', 'name' and 'grouped' are used, see filterValues() below
						ItemGroup::find($item->id)->items->all()
					);
					$result = self::filterValues($items);

					if(count($result) == 1) { // specific item found
						$item = reset($result);
					}
				}

				$this->redirectRoute($item->grouped ? 'shop.itemGroup.view' : 'shop.item.view', [$item->id, Helper::GetItemSlug($item->name)]);
			}

			$view = view('components.shop.item-list');

			if($this->search !== '')
				$view->title("Suche „{$this->search}“");

			return $view;
		}

		/**
		 * @param array<ItemListEntry> $elements
		 *
		 * @return array<ItemListEntry>
		 */
		private function filterValues(array $elements): array {
			// remove accents, see https://stackoverflow.com/a/76733861
			$transliterator = Transliterator::createFromRules(':: NFD; :: [:Mn:] Remove; :: NFC;');
			$search         = $transliterator->transliterate($this->search);
			$searchParts    = preg_split('/[^\pL0-9]+/u', $search, flags: PREG_SPLIT_NO_EMPTY); // split at any chars that are neither letters nor digits 0-9

			if(!$searchParts) { // invalid or empty search (e.g. consisting only of separator chars)
				$this->search = '';
				return $elements;
			}

			$regex = '/(?=.*' . implode(')(?=.*', $searchParts) . ').*/i'; // Build search regex. All search parts are required. No need for escaping as our search parts only consist of [a-zA-Z0-9]+

			return array_filter($elements, function ($entry) use ($regex, $transliterator) {
				// First check if name matches to skip querying database
				if(preg_match($regex, $transliterator->transliterate($entry->name)))
					return true;

				$values = $entry->grouped
					? ItemGroup::find($entry->id)->getSearchFilterValues($this->searchDescription)
					: Item::find($entry->id)->getSearchFilterValues($this->searchDescription);

				$searchTarget = implode(',', $values); // implode with any separator not contained in search expression to prevent search values from spilling over

				return preg_match($regex, $transliterator->transliterate($searchTarget));
			});
		}
	}

<?php

	namespace App\Http\Components\Shop;

	use Illuminate\Support\Facades\DB;
	use Livewire\Attributes\Computed;
	use Livewire\Attributes\Layout;
	use Livewire\Component;

	#[Layout('layouts.shop')]
	class ItemList extends Component {

		/**
		 * Gets all elements that should be displayed in shop item list. Grouped items will be returned as a single element, ungrouped items will be returned unchanged.
		 * Result is ordered by the amount of non-cancelled orders that contain the corresponding ungrouped item or an item of the corresponding group.
		 *
		 * TODO visibility
		 *
		 * @return array
		 */
		#[Computed]
		public function items(): array {
			return DB::select(<<<SQL
				WITH singleItems AS (SELECT items.id,
				                            name,
				                            FIRST_VALUE(images.path) OVER (PARTITION BY item_id ORDER BY images.id) imagePath,
				                            0                                                                       grouped,
				                            (SELECT COUNT(DISTINCT orders.id)
				                             FROM orders
				                             JOIN order_item ON orders.id = order_item.order_id
				                             WHERE orders.status != 'cancelled' AND
				                                   order_item.item_id = items.id)                                   orders
				                     FROM items
				                     LEFT JOIN images ON items.id = images.item_id
				                     WHERE item_group_id IS NULL),
				     groupedItems AS (SELECT item_groups.id,
				                             item_groups.name,
				                             FIRST_VALUE(images.path) OVER (PARTITION BY item_groups.id ORDER BY images.id IS NULL, items.id,images.id),
				                             1,
				                             (SELECT COUNT(DISTINCT orders.id)
				                              FROM orders
				                              JOIN order_item ON orders.id = order_item.order_id
				                              JOIN items innerItems ON order_item.item_id = innerItems.id
				                              WHERE orders.status != 'cancelled' AND
				                                    innerItems.item_group_id = item_groups.id)
				                      FROM item_groups
				                      JOIN items ON item_groups.id = items.item_group_id
				                      LEFT JOIN images ON items.id = images.item_id)
				SELECT * FROM singleItems GROUP BY id
				UNION ALL
				SELECT * FROM groupedItems GROUP BY id
				ORDER BY orders DESC, name
				SQL
			);
		}

		public function render() {
			return view('components.shop.item-list');
		}
	}

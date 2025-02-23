<?php

	namespace App\Policies;

	use App\Models\Item;
	use App\Models\ItemGroup;
	use App\Models\User;
	use Illuminate\Auth\Access\Response;
	use Illuminate\Support\Facades\Gate;

	class ItemGroupPolicy {
		/**
		 * Groups without items are not visible.
		 * Groups containing at least a single visible item are visible to everyone.
		 * Groups containing only invisible items are visible to all who can view those items.
		 */
		public function view(?User $user, ItemGroup $group): Response {
			return $group->items->first(fn(Item $item) => Gate::allows('view', $item)) != null
				? Response::allow()
				: Response::denyAsNotFound();
		}
	}

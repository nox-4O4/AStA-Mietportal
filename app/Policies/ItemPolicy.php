<?php

	namespace App\Policies;

	use App\Models\Item;
	use App\Models\User;
	use Illuminate\Auth\Access\Response;

	class ItemPolicy {
		/**
		 * Visible items can always be seen. Invisible items may only be seen be logged-in users.
		 */
		public function view(?User $user, Item $item): Response {
			return $item->visible || $user
				? Response::allow()
				: Response::denyAsNotFound();
		}
	}

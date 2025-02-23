<?php

	namespace App\Policies;

	use App\Models\DTOs\ItemListEntry;
	use App\Models\User;
	use Illuminate\Auth\Access\Response;

	class ItemListEntryPolicy {
		/**
		 * Visible items can always be seen. Invisible items may only be seen be logged-in users.
		 */
		public function view(?User $user, ItemListEntry $itemListEntry): Response {
			return $itemListEntry->visible || $user
				? Response::allow()
				: Response::denyAsNotFound();
		}
	}

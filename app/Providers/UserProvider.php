<?php

	namespace App\Providers;

	use App\Models\User;
	use Illuminate\Auth\EloquentUserProvider;
	use Illuminate\Contracts\Auth\Authenticatable as UserContract;
	use SensitiveParameter;

	class UserProvider extends EloquentUserProvider {

		public function validateCredentials(UserContract $user, #[SensitiveParameter] array $credentials): bool {
			if(!parent::validateCredentials($user, $credentials))
				return false;

			return $user instanceof User && $user->enabled;
		}
	}

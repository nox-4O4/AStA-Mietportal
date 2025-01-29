<?php

	namespace App\Providers;

	use App\Models\User;
	use Illuminate\Auth\EloquentUserProvider;
	use Illuminate\Contracts\Auth\Authenticatable as UserContract;
	use Illuminate\Contracts\Hashing\Hasher as HasherContract;
	use Illuminate\Database\Eloquent\Builder;
	use SensitiveParameter;

	class UserProvider extends EloquentUserProvider {

		public function __construct(HasherContract $hasher, $model) {
			parent::__construct($hasher, $model);

			// query builder is used for "remember me" functionality (retrieveByToken), for validating current session (retrieveById) and for sending passwort reset mail (retrieveByCredentials)
			$this->withQuery(fn(Builder $query) => $query->where('enabled', true));
		}

		// used for login
		public function validateCredentials(UserContract $user, #[SensitiveParameter] array $credentials): bool {
			if(!parent::validateCredentials($user, $credentials))
				return false;

			return $user instanceof User && $user->enabled;
		}
	}

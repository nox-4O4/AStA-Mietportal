<?php

	namespace App\Models;

	use App\Enums\UserRole;
	use App\Notifications\AccountCreated;
	use App\Notifications\ResetPassword;
	use DateTime;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Ramsey\Collection\Collection;
	use SensitiveParameter;

	/**
	 * @property string              $forename
	 * @property string              $surname
	 * @property string              $email
	 * @property ?string             $password
	 * @property UserRole            $role
	 * @property bool                $enabled
	 * @property DateTime            $last_login
	 * @property string              $remember_token
	 * @property Collection<Comment> $comments
	 */
	class User extends Authenticatable {
		use Notifiable;

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'username',
			'forename',
			'surname',
			'email',
			'password',
			'role',
			'enabled',
		];

		/**
		 * The attributes that should be hidden for serialization.
		 *
		 * @var array<string>
		 */
		protected $hidden = [
			'password',
			'remember_token',
		];

		public function updateLastLogin(): void {
			$this->last_login = new DateTime('now');
			$this->update();
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'password'   => 'hashed',
				'role'       => UserRole::class,
				'enabled'    => 'bool',
				'last_login' => 'datetime',
			];
		}

		public function sendPasswordResetNotification(#[SensitiveParameter] $token): void {
			if($this->password == null)
				$this->notify(new AccountCreated($token, $this));

			else
				$this->notify(new ResetPassword($token, $this));
		}

		public function comments(): HasMany {
			return $this->hasMany(Comment::class)->chaperone();
		}

		public function name(): Attribute {
			return Attribute::get(fn() => "$this->forename $this->surname");
		}
	}

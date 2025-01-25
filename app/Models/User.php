<?php

	namespace App\Models;

	use App\Enums\UserRole;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;

	/**
	 * @property string   $forename
	 * @property string   $surname
	 * @property string   $email
	 * @property string   $password
	 * @property UserRole $role
	 * @property bool     $enabled
	 */
	class User extends Authenticatable {
		use Notifiable;

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var list<string>
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
		 * @var list<string>
		 */
		protected $hidden = [
			'password',
			'remember_token',
		];

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'password' => 'hashed',
				'role'     => UserRole::class,
				'enabled'  => 'bool',
			];
		}
	}

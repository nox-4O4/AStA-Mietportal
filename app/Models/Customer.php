<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	/**
	 * @property string  $forename
	 * @property string  $surname
	 * @property ?string $legalname
	 * @property ?string $street
	 * @property ?string $number
	 * @property ?string $zipcode
	 * @property ?string $city
	 * @property string  $email
	 * @property string  $mobile
	 */
	class Customer extends Model {

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'forename',
			'surname',
			'legalname',
			'street',
			'number',
			'zipcode',
			'city',
			'email',
			'mobile',
		];

		public function orders(): HasMany {
			return $this->hasMany(Order::class)->orderBy('id')->chaperone();
		}

		public function getNameAttribute(): string {
			return $this->forename . ' ' . $this->surname;
		}
	}

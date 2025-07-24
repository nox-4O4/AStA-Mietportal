<?php

	namespace App\Models;

	use Carbon\CarbonImmutable;
	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	/**
	 * @property int              $id
	 * @property string           $forename
	 * @property string           $surname
	 * @property ?string          $legalname
	 * @property ?string          $street
	 * @property ?string          $number
	 * @property ?string          $zipcode
	 * @property ?string          $city
	 * @property string           $email
	 * @property ?string          $mobile
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
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

		public function name(): Attribute {
			return Attribute::get(fn() => $this->forename . ' ' . $this->surname);
		}
	}

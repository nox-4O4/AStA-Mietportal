<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	/**
	 * @property string   $name
	 * @property string   $description
	 * @property DateTime $created_at
	 * @property DateTime $updated_at
	 */
	class ItemGroup extends Model {

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'name',
			'description',
		];

		public function items(): HasMany {
			return $this->hasMany(Item::class)->chaperone();
		}
	}

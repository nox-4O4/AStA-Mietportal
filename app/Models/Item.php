<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Casts\Attribute;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;

	/**
	 * @property string     $name
	 * @property string     $description
	 * @property int        $amount
	 * @property bool       $available
	 * @property bool       $visible
	 * @property float      $price
	 * @property float      $deposit
	 * @property ?ItemGroup $itemGroup
	 */
	class Item extends Model {
		protected $fillable = [
			'name',
			'description',
			'amount',
			'available',
			'visible',
			'price',
			'deposit',
		];

		protected function name(): Attribute {
			return Attribute::make(fn(string $value) => $this->itemGroup ? "{$this->itemGroup->name} - $value" : $value);
		}

		public function itemGroup(): BelongsTo {
			return $this->belongsTo(ItemGroup::class);
		}

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'available' => 'bool',
				'visible'   => 'bool',
				'price'     => 'float',
				'deposit'   => 'float',
				'amount'    => 'integer',
			];
		}
	}

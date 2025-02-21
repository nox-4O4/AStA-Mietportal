<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Database\Eloquent\Relations\HasManyThrough;

	/**
	 * @property int       $id
	 * @property string    $name
	 * @property string    $description
	 * @property ?DateTime $created_at
	 * @property ?DateTime $updated_at
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

		public function itemImages(): HasManyThrough {
			return $this->hasManyThrough(Image::class, Item::class)->orderBy('item_id')->orderBy('id');
		}

		public function image(): BelongsTo {
			return $this->belongsTo(Image::class);
		}

		public function getSearchFilterValues(bool $includeDescription): array {
			$items      = $this->items()->where('visible', true)->get()->all();
			$itemValues = array_merge(...array_map(fn(Item $item) => $item->getSearchFilterValues($includeDescription), $items));

			return [
				...($includeDescription ? [$this->description] : []),
				...($itemValues ?: [$this->name]) // omit own name when items exist as own name is part of item name
			];
		}
	}

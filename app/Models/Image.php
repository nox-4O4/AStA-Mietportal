<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;

	/**
	 * @property string $path
	 * @property Item   $item
	 */
	class Image extends Model {
		protected $fillable = [
			'path',
			'item_id'
		];

		public function item(): BelongsTo {
			return $this->belongsTo(Item::class);
		}
	}

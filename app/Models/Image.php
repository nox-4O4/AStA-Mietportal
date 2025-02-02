<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Support\Facades\Storage;

	/**
	 * @property string $path
	 * @property Item   $item
	 */
	class Image extends Model {
		protected $fillable = [
			'path',
			'item_id'
		];

		protected static function booted(): void {
			static::deleted(function (Image $item) {
				Storage::disk('public')->delete($item->path);
			});
		}

		public function item(): BelongsTo {
			return $this->belongsTo(Item::class);
		}
	}

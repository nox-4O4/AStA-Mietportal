<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Support\Facades\Storage;

	/**
	 * @property int       $id
	 * @property string    $path
	 * @property int       $item_id
	 * @property Item      $item
	 * @property ?DateTime $created_at
	 * @property ?DateTime $updated_at
	 */
	class Image extends Model {
		protected $fillable = [
			'path',
			'item_id'
		];

		protected static function booted(): void {
			static::created(function (Image $image) {
				if($image->item->itemGroup && !$image->item->itemGroup->image)
					$image->item->itemGroup->image()->associate($image)->save();
			});
			static::deleted(function (Image $image) {
				Storage::disk('public')->delete($image->path);

				if($image->item->refresh()->itemGroup && !$image->item->itemGroup->image) {
					if($image->item->images->isNotEmpty())
						$newImage = $image->item->images->first();
					else
						$newImage = $image->item->itemGroup->itemImages()->first();

					if($newImage)
						$image->item->itemGroup->image()->associate($newImage)->save();
				}
			});
		}

		public function item(): BelongsTo {
			return $this->belongsTo(Item::class);
		}
	}

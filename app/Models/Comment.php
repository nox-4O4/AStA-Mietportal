<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;

	/**
	 * @property int      $id
	 * @property ?User    $user
	 * @property Order    $order
	 * @property string   $comment
	 * @property DateTime $created_at
	 * @property DateTime $updated_at
	 */
	class Comment extends Model {
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'comment',
		];

		public function user(): BelongsTo {
			return $this->belongsTo(User::class);
		}

		public function order(): BelongsTo {
			return $this->belongsTo(Order::class);
		}
	}

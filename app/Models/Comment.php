<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;

	/**
	 * @property int       $id
	 * @property ?int      $user_id
	 * @property ?User     $user
	 * @property int       $order_id
	 * @property Order     $order
	 * @property string    $comment
	 * @property ?DateTime $created_at // cannot be non-null due to database behaviour. See https://github.com/laravel/framework/issues/12060 and https://github.com/laravel/ideas/issues/874#issuecomment-343639163
	 * @property ?DateTime $updated_at
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

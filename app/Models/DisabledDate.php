<?php

	namespace App\Models;

	use DateTime;
	use Illuminate\Database\Eloquent\Model;

	/**
	 * @property int       $id
	 * @property DateTime  $start
	 * @property DateTime  $end
	 * @property string    $site_notice
	 * @property string    $comment
	 * @property bool      $active
	 * @property ?DateTime $created_at
	 * @property ?DateTime $updated_at
	 */
	class DisabledDate extends Model {

		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<string>
		 */
		protected $fillable = [
			'start',
			'end',
			'site_notice',
			'comment',
			'active',
		];

		/**
		 * Get the attributes that should be cast.
		 *
		 * @return array<string, string>
		 */
		protected function casts(): array {
			return [
				'active' => 'bool',
				'start'  => 'date',
				'end'    => 'date',
			];
		}
	}

<?php

	namespace App\Models;

	use Carbon\CarbonImmutable;
	use DateTimeInterface;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;

	/**
	 * @property int              $id
	 * @property CarbonImmutable  $start
	 * @property CarbonImmutable  $end
	 * @property string           $site_notice
	 * @property string           $comment
	 * @property bool             $active
	 * @property ?CarbonImmutable $created_at
	 * @property ?CarbonImmutable $updated_at
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

		public static function overlapsWithRange(DateTimeInterface $start, DateTimeInterface $end): bool {
			return (bool) DB::select(
				<<<SQL
				SELECT 1
				FROM disabled_dates
				WHERE active AND
				      :start <= end AND
				      :end >= start
				LIMIT 1
				SQL,
				[
					'start' => $start->format('Y-m-d'),
					'end'   => $end->format('Y-m-d'),
				]
			);
		}
	}

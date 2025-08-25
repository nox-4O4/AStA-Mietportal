<?php

	namespace App\Models;

	use Carbon\CarbonImmutable;
	use DateTimeInterface;
	use Illuminate\Database\Eloquent\Collection;
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

		/**
		 * Returns true if there is an intersection between any disabled date and the specified range or day. If end is omitted, only the day specified by start is checked.
		 * Inactive ranges are ignored.
		 */
		public static function anyOverlapsWithRange(DateTimeInterface $start, ?DateTimeInterface $end = null): bool {
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
					'end'   => ($end ?? $start)->format('Y-m-d'),
				]
			);
		}

		/**
		 * Returns true if there is an intersection between this disabled date and the specified range or day. If end is omitted, only the day specified by start is checked.
		 * Inactive ranges are ignored.
		 */
		public function overlapsWithRange(DateTimeInterface $start, ?DateTimeInterface $end = null): bool {
			return $this->active && $this->start->lte($end ?? $start) && $this->end->gte($start);
		}

		/**
		 * @return Collection<static>
		 */
		public static function getOverlappingRanges(DateTimeInterface $start, ?DateTimeInterface $end = null): Collection {
			return static::where('active', true)
			             ->where('start', '<=', $end ?? $start)
			             ->where('end', '>=', $start)
			             ->orderBy('start')
			             ->orderBy('end')
			             ->get();
		}
	}

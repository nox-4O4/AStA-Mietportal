<?php

	namespace App\Models\DTOs;

	use App\Util\DateCaster;
	use Carbon\CarbonImmutable;
	use Spatie\LaravelData\Attributes\WithCast;
	use Spatie\LaravelData\Data;

	class ItemAvailability extends Data {
		public function __construct(
			#[WithCast(DateCaster::class, format: "Y-m-d")]
			public CarbonImmutable $date,
			public int             $available,
		) {
		}
	}

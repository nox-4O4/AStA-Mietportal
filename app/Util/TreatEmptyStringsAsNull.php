<?php

	namespace App\Util;

	use Spatie\LaravelData\Casts\Cast;
	use Spatie\LaravelData\Casts\Uncastable;
	use Spatie\LaravelData\Support\Creation\CreationContext;
	use Spatie\LaravelData\Support\DataProperty;

	class TreatEmptyStringsAsNull implements Cast {
		public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed {
			if($value === '')
				return null;

			return Uncastable::create();
		}
	}

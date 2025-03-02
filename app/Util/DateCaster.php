<?php

	namespace App\Util;

	use DateTimeInterface;
	use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
	use Spatie\LaravelData\Casts\Uncastable;

	class DateCaster extends DateTimeInterfaceCast {
		protected function castValue(?string $type, mixed $value): Uncastable|null|DateTimeInterface {
			$parentValue = parent::castValue($type, $value);

			if($parentValue instanceof DateTimeInterface)
				return $parentValue->setTime(0, 0); // this is a date caster, not a date time caster, so we return dates without time information

			return $parentValue;
		}
	}

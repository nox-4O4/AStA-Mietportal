<?php

	namespace App\Exceptions;

	use Carbon\CarbonImmutable;
	use Exception;

	class AmountExceededException extends Exception {
		public function __construct(public readonly CarbonImmutable $date) {
			parent::__construct("Available amount on {$this->date->format('Y-m-d')} exceeded.");
		}

		public static function forDate(CarbonImmutable $date): AmountExceededException {
			return new AmountExceededException($date);
		}
	}

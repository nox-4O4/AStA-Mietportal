<?php

	namespace App\Models\DTOs;

	use Spatie\LaravelData\Data;

	class CheckoutData extends Data {
		public function __construct(
			public string  $forename,
			public string  $surname,
			public ?string $legalname,
			public string  $street,
			public string  $number,
			public string  $zip,
			public string  $city,
			public string  $email,
			public ?string $mobile,
			public float   $rate,
			public string  $note,
			public string  $eventName,
			public string  $cartHash,
		) {
		}

		public function getHash(): string {
			return sha1(implode("\0", $this->all()));
		}
	}

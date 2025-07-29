<?php

	namespace App\Enums;

	use Attribute;

	#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
	readonly class RoleAttribute {
		public function __construct(
			public string $name,
			public string $explanation,
			public array  $capabilities,
		) {
		}
	}

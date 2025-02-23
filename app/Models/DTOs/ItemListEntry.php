<?php

	namespace App\Models\DTOs;

	use Spatie\LaravelData\Data;

	class ItemListEntry extends Data {
		public function __construct(
			public int     $id,
			public string  $name,
			public ?string $imagePath,
			public bool    $grouped,
			public int     $orders,
			public bool    $visible,
		) {
		}
	}

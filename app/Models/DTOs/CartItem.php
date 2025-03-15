<?php

	namespace App\Models\DTOs;

	use App\Models\Item;
	use App\Util\DateCaster;
	use App\Util\ModelIdConverter;
	use App\Util\TreatEmptyStringsAsNull;
	use ArrayAccess;
	use Carbon\CarbonImmutable;
	use Spatie\LaravelData\Attributes\WithCast;
	use Spatie\LaravelData\Attributes\WithCastAndTransformer;
	use Spatie\LaravelData\Attributes\WithTransformer;
	use Spatie\LaravelData\Data;
	use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

	class CartItem extends Data implements ArrayAccess { // implementing ArrayAccess for easier validation
		public function __construct(
			#[WithCastAndTransformer(ModelIdConverter::class, type: Item::class)]
			public ?Item           $item,

			#[WithCast(DateCaster::class, format: "Y-m-d")]
			#[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d")]
			public CarbonImmutable $start,

			#[WithCast(DateCaster::class, format: "Y-m-d")]
			#[WithTransformer(DateTimeInterfaceTransformer::class, format: "Y-m-d")]
			public CarbonImmutable $end,

			#[WithCast(TreatEmptyStringsAsNull::class)]
			public ?int            $amount,
			public string          $comment,
		) {
		}

		public function offsetExists(mixed $offset): bool {
			return isset($this->$offset);
		}

		public function offsetGet(mixed $offset): mixed {
			return $this->$offset;
		}

		public function offsetSet(mixed $offset, mixed $value): void {
			$this->$offset = $value;
		}

		public function offsetUnset(mixed $offset): void {
			unset($this->$offset);
		}
	}

<?php

	namespace App\Util;

	use Illuminate\Database\Eloquent\Model;
	use LogicException;
	use Spatie\LaravelData\Casts\Cast;
	use Spatie\LaravelData\Casts\IterableItemCast;
	use Spatie\LaravelData\Casts\Uncastable;
	use Spatie\LaravelData\Support\Creation\CreationContext;
	use Spatie\LaravelData\Support\DataProperty;
	use Spatie\LaravelData\Support\Transformation\TransformationContext;
	use Spatie\LaravelData\Transformers\Transformer;

	/**
	 * @template T of Model
	 */
	class ModelIdConverter implements Transformer, Cast, IterableItemCast {

		/**
		 * @param class-string<T> $type
		 */
		public function __construct(protected string $type) {
			if(!is_a($this->type, Model::class, true))
				throw new LogicException('Only models can be converted by ModelIdConverter');
		}

		/**
		 * @return Uncastable|T|null
		 * @noinspection PhpDocSignatureInspection signature matches; T can only be Model. Probably PHPStorm bug.
		 */
		public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): Uncastable|Model|null {
			if(!is_string($value) && !is_int($value))
				return Uncastable::create();

			return $this->type::find($value);
		}

		/**
		 * @return Uncastable|T|null
		 * @noinspection PhpDocSignatureInspection signature matches; T can only be Model. Probably PHPStorm bug.
		 */
		public function castIterableItem(DataProperty $property, mixed $value, array $properties, CreationContext $context): Uncastable|Model|null {
			return $this->cast($property, $value, $properties, $context);
		}

		public function transform(DataProperty $property, mixed $value, TransformationContext $context): int|string {
			return $value->{$value->getKeyName()};
		}
	}

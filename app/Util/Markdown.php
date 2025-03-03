<?php

	namespace App\Util;

	use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Str;
	use InvalidArgumentException;
	use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
	use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
	use League\CommonMark\Extension\Table\Table;
	use Stringable;

	class Markdown implements CastsAttributes, Stringable {
		public function __construct(public ?string $markdownContent = null) { }

		public function isEmpty(): bool {
			return $this->markdownContent === null || trim($this->markdownContent) === '';
		}

		public function __toString(): string {
			return $this->markdownContent ?? '';
		}

		public function render(): string {
			if($this->markdownContent === null)
				return '';

			return Str::markdown(
				$this->markdownContent,
				[
					'html_input'         => 'strip',
					'allow_unsafe_links' => false,
					'max_nesting_level'  => 10,
					'default_attributes' => [
						// add bootstrap styling
						Table::class      => ['class' => 'table'],
						BlockQuote::class => ['class' => 'blockquote'],
					],
				],
				[
					new DefaultAttributesExtension(),
				]
			);
		}

		public function get(Model $model, string $key, mixed $value, array $attributes) {
			return new static($value);
		}

		public function set(Model $model, string $key, mixed $value, array $attributes): ?string {
			if($value instanceof Markdown)
				return $value->markdownContent;

			if($value instanceof Stringable || is_string($value))
				return $value;

			throw new InvalidArgumentException('The given value is not stringable.');
		}
	}

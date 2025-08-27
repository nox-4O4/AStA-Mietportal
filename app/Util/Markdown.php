<?php

	namespace App\Util;

	use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Str;
	use InvalidArgumentException;
	use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
	use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
	use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
	use League\CommonMark\Extension\Table\Table;
	use League\CommonMark\Node\Block\Paragraph;
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
						Paragraph::class  => ['class' => 'md-paragraph'],
						Link::class       => [
							'target' => static function (Link $link) {
								$linkHost = parse_url($link->getUrl(), PHP_URL_HOST);
								$appHost  = parse_url(config('app.url'), PHP_URL_HOST);

								if($linkHost != $appHost)
									return '_blank';

								return null;
							}
						]
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

			if($value instanceof Stringable || is_string($value) || $value === null)
				return $value;

			throw new InvalidArgumentException('The given value is not stringable.');
		}
	}

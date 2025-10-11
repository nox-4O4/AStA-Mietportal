<?php

	namespace App\Util;

	use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Routing\Exceptions\UrlGenerationException;
	use Illuminate\Support\Facades\Route;
	use Illuminate\Support\Str;
	use InvalidArgumentException;
	use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
	use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
	use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
	use League\CommonMark\Extension\Table\Table;
	use League\CommonMark\Node\Block\Paragraph;
	use League\CommonMark\Node\Inline\Text;
	use Stringable;
	use Symfony\Component\Routing\Exception\RouteNotFoundException;

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
								$linkParts = parse_url($link->getUrl());
								$appHost   = parse_url(config('app.url'), PHP_URL_HOST);

								// Adds support for route links. Syntax: route:name_of_route?route_parameter=value#external
								// Route parameters and #external fragment are optional.
								if(isset($linkParts['scheme'], $linkParts['path']) && $linkParts['scheme'] == 'route') {
									if(!Route::has($linkParts['path'])) {
										report(new RouteNotFoundException("Die via Markdown referenzierte Route '{$linkParts['path']}' existiert nicht."));
										$link->firstChild()->replaceWith(new Text('(Link konnte nicht geladen werden: Route existiert nicht)'));

										return null;
									}

									isset($linkParts['query'])
										? parse_str($linkParts['query'], $routeParameters)
										: $routeParameters = [];

									try {
										$link->setUrl(route($linkParts['path'], $routeParameters));
									} catch(UrlGenerationException $ex) {
										report($ex);
										$link->firstChild()->replaceWith(new Text('(Link konnte nicht geladen werden: Parameter fehlt)'));

										return null;
									}

									if($linkParts['fragment'] ?? '' == 'external')
										return '_blank';

								} else if(isset($linkParts['host']) && $linkParts['host'] != $appHost) {
									return '_blank';
								}

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

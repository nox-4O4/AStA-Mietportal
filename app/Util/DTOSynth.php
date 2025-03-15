<?php

	namespace App\Util;

	use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
	use Spatie\LaravelData\Data;

	class DTOSynth extends Synth {
		public static string $key = 'dto';

		static function match(mixed $target): bool {
			return is_a($target, Data::class);
		}

		public function dehydrate(Data $target): array {
			return [$target->toArray(), ['c' => get_class($target)]];
		}

		/**
		 * @var array{'c': class-string<Data>} $meta
		 */
		public function hydrate(mixed $value, array $meta): Data {
			return $meta['c']::from($value);
		}

		public function get(&$target, $key) {
			return $target->$key;
		}

		public function set(Data &$target, string $key, mixed $value): void {
			$data       = $target->toArray();
			$data[$key] = $value;
			$target     = $target::from($data);
		}
	}

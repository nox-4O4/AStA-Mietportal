<?php

	namespace App\Util;

	use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Support\Str;

	class Helper {
		public static function GetItemSlug(string $name): string {
			return Str::slug($name, language: 'de');
		}

		public static function HashCollection(Collection $collection, string $attribute = 'id'): string {
			return sha1(implode("\0", $collection->pluck($attribute)->all()));
		}

		public static function EscapeMarkdown(string $text): string {
			return addcslashes($text, '\\`*_{}[]()#+-.!|');
		}

		public static function getSteppedDeposit(float $totalDeposit): float {
			$depositSteps = config('shop.deposit_steps');
			rsort($depositSteps);

			// use maximum step that is smaller than calculated deposit
			foreach($depositSteps as $step) {
				if($step <= $totalDeposit) {
					$totalDeposit = $step;
					break;
				}
			}

			return $totalDeposit;
		}
	}

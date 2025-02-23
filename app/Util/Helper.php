<?php

	namespace App\Util;

	use Illuminate\Support\Str;

	class Helper {
		public static function GetItemSlug(string $name): string {
			return Str::slug($name, language: 'de');
		}
	}

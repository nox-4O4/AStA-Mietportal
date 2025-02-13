<?php

	namespace Database\Seeders;

	use Illuminate\Database\Seeder;
	use Illuminate\Support\Facades\DB;

	class ContentSeeder extends Seeder {

		private static array $defaultContents = [
		];

		public function run(): void {
			$now = now(); // timestamps are not managed by Laravel when using DB::table('...')->insert operations
			foreach(self::$defaultContents as $name => $description) {
				DB::table('contents')->insertOrIgnore(
					[
						'name'        => $name,
						'description' => $description,
						'content'     => '',
						'created_at'  => $now,
						'updated_at'  => $now,
					]);
			}
		}
	}

<?php

	namespace Database\Seeders;

	use Illuminate\Database\Seeder;
	use Illuminate\Support\Facades\DB;

	class ContentSeeder extends Seeder {

		private static array $defaultContents = [
			'shop.top'                      => 'Wird am Anfang der Übersichtsseite im Shop oberhalb der Artikel dargestellt.',
			'checkout.tos'                  => 'Der Text, dem Benutzer beim Aufgeben der Bestellung zustimmen müssen. Leer lassen, um keine Bestätigung zu erfordern.',
			'checkout.success'              => 'Wird angezeigt, nachdem eine Bestellung erfolgreich getätigt wurde.',
			'mail.orderReceiptConfirmation' => 'Der E-Mail-Text in der Eingangsbestätigung für den Kunden. Die Anrede wird automatisch generiert.',
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

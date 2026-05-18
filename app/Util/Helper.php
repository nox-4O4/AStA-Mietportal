<?php

	namespace App\Util;

	use Dompdf\Dompdf;
	use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\Str;
	use IntlDateFormatter;
	use IntlDatePatternGenerator;

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

		public static function renderPDFTemplate(string $template, array $templateData = []): Dompdf {
			$dompdf = new Dompdf(
				[
					'isPdfAEnabled'    => true,
					'fontCache'        => storage_path('framework/cache/fonts/'),
					'defaultPaperSize' => 'a4',
				]
			);

			// remap sans-serif font to an embeddable font for PDF/A compatibility
			$font_metrics = $dompdf->getFontMetrics();
			$font_metrics->setFontFamily('sans-serif', $font_metrics->getFamily('DejaVu Sans'));

			$dompdf->loadHtml(Blade::render($template, $templateData));
			$dompdf->addInfo('Creator', 'AStA-Mietportal');
			$dompdf->render();

			return $dompdf;
		}

		/**
		 * Returns a localized date formatter for the corresponding ICU pattern skeleton.
		 * See https://unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table for possible skeleton pattern values.
		 *
		 * @param string $skeleton A pattern skeleton according to ICU
		 *
		 * @return IntlDateFormatter Returns a (possibly cached) date formatter instance.
		 */
		public static function getDateFormatter(string $skeleton): IntlDateFormatter {
			static $formatters;

			if(!isset($formatters[$skeleton])) {
				$locale                = config('app.locale');
				$pattern               = IntlDatePatternGenerator::create($locale)->getBestPattern($skeleton);
				$formatters[$skeleton] = IntlDateFormatter::create($locale, pattern: $pattern);
			}

			return $formatters[$skeleton];
		}
	}

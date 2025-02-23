<?php

	namespace App\Providers\PriceCalculation;

	use App\Contracts\PriceCalculation;
	use App\Models\Item;
	use Carbon\CarbonInterface;
	use Illuminate\Support\Facades\Blade;

	class ConstantPriceCalculation implements PriceCalculation {
		public function __construct(array $configuration) { }

		public function calculatePrice(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): float {
			return $item->price;
		}

		public function displayPriceInformation(Item $item): string {
			return Blade::render('<p>Preis: <b>@money($item->price)</b></p>', ['item' => $item]);
		}
	}

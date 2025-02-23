<?php

	namespace App\Contracts;

	use App\Models\Item;
	use Carbon\CarbonInterface;
	use DateTime;

	interface PriceCalculation {

		public function __construct(array $configuration);

		public function calculatePrice(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): float;

		public function displayPriceInformation(Item $item): string;
	}

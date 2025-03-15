<?php

	namespace App\Contracts;

	use App\Models\Item;
	use Carbon\CarbonInterface;

	interface PriceCalculation {

		public function __construct(array $configuration);

		public function calculatePrice(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): float;

		public function getChargedDays(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): ?int;

		public function displayPriceInformation(Item $item): string;
	}

<?php

	namespace App\Providers\PriceCalculation;

	use App\Contracts\PriceCalculation;
	use App\Models\Item;
	use Carbon\CarbonInterface;
	use Illuminate\Support\Facades\Blade;
	use League\Config\Exception\InvalidConfigurationException;

	class AsymptoticPriceCalculation implements PriceCalculation {
		private readonly float $increment;
		private readonly float $startValue;

		public function __construct(array $configuration) {
			if(!isset($configuration['increment'], $configuration['start_value']))
				throw new InvalidConfigurationException('Price calculation configuration "increment" or "start_value" is missing.');

			[
				'increment'   => $this->increment,
				'start_value' => $this->startValue
			] = $configuration;
		}

		public function calculatePrice(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): float {
			$days   = $this->getChargedDays($item, $startDate, $endDate);
			$factor = $this->startValue + $this->increment * ($days - 1);

			return $item->price * $factor;
		}

		public function displayPriceInformation(Item $item): string {
			return Blade::render(/** @lang Blade */ <<<'Blade'
				<div class="mb-3">
					<p class="mb-0">Preis für einen Tag: <b>@money($item->price * $startValue)</b></p>
					<p class="mb-0">Für jeden weiteren Tag: <span class="fw-semibold">@money($item->price * $increment)</span></p>
					@if($item->price)
						<p class="small text-muted mb-0">Bei einem Zeitraum von mehreren Tagen wird der letzte Tag nicht mitgezählt.</p>
					@endif
				</div>
				Blade, ['item' => $item, 'startValue' => $this->startValue, 'increment' => $this->increment]
			);
		}

		public function getChargedDays(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): int {
			return max(1, (int) $startDate->diffInDays($endDate));
		}
	}

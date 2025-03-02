<?php

	namespace App\Providers\PriceCalculation;

	use App\Contracts\PriceCalculation;
	use App\Models\Item;
	use Carbon\CarbonInterface;
	use Illuminate\Support\Facades\Blade;
	use League\Config\Exception\InvalidConfigurationException;

	class MultiplicativePriceCalculation implements PriceCalculation {
		private readonly array $multipliers;

		public function __construct(array $configuration) {
			$this->multipliers = $configuration['multipliers'] ?? throw new InvalidConfigurationException('Price calculation configuration "multipliers" is missing.');
		}

		public function calculatePrice(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): float {
			$days          = $this->getChargedDays($item, $startDate, $endDate);
			$multiplierKey = max(array_filter(array_keys($this->multipliers), fn(int $d) => $d <= $days) ?: [0]);

			if($multiplierKey <= 0)
				return $item->price * $days;
			else
				return $item->price * $this->multipliers[$multiplierKey] * $days;
		}

		public function displayPriceInformation(Item $item): string {
			return Blade::render(/** @lang Blade */ <<<'Blade'
				<div class="mb-3">
					<p class="mb-0">Preis: <b>@money($item->price)</b></p>
					@if($item->price)
						@if($multipliers)
							<details class="small text-muted">
							    <summary>Staffelpreise vorhanden</summary>
							    <x-multiplicative-price-discount class="mb-1" :price="$item->price" :multipliers="$multipliers" />
							    <p class="mb-0">Bei einem Zeitraum von mehreren Tagen wird der letzte Tag nicht mitgezählt.</p>
							</details>
						@else
							<p class="small text-muted mb-0">Bei einem Zeitraum von mehreren Tagen wird der letzte Tag nicht mitgezählt.</p>
						@endif
					@endif
				</div>
				Blade, ['item' => $item, 'multipliers' => $this->multipliers]
			);
		}

		public function getChargedDays(Item $item, CarbonInterface $startDate, CarbonInterface $endDate): int {
			return max(1, (int) $startDate->diffInDays($endDate));
		}
	}

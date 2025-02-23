<?php

	return [
		'price_calculation' => 'asymptotic', // name of one of configured prive_calculation_providers below

		'prive_calculation_providers' => [
			// Price is calculated by base price (start_value factor) plus a reduced amount for any additional day (increment factor).
			// When calculating resulting discount in terms of price per day, discount will converge asymptotically to increment value (when increment != start_value).
			'asymptotic'     => [
				'class'         => \App\Providers\PriceCalculation\AsymptoticPriceCalculation::class,
				'configuration' => [
					'start_value' => 1,
					'increment'   => 1 / 2,
				],
			],

			// assign a fixed multiplicative discount to the item price per day, depending on the booking duration
			'multiplicative' => [
				'class'         => \App\Providers\PriceCalculation\MultiplicativePriceCalculation::class,
				'configuration' => [
					// Keys are days, values are price multipliers. Omitted days will be treated as range starting from previous entry. Day 1 defaults to factor 1.
					'multipliers' => [
						// make sure total price factor remains strictly increasing for *every* day! (Calculate by: discount x days)
						2 => 4 / 5, // 20% discount, total factor 1.6
						3 => 3 / 5, // 40% discount, total factors 1.8 (3 days) and 2.4 (4 days)
						5 => 1 / 2, // 50% discount, total factors from 2.5 onwards (2.5, 3.0, 3.5, ...)
					],
				],
			],

			// duration does not affect price
			'constant'       => [
				'class' => \App\Providers\PriceCalculation\ConstantPriceCalculation::class,
			],
		],
	];

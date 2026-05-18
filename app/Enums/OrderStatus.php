<?php

	namespace App\Enums;

	// TODO perhaps use attributes on cases for short name and sort index?
	enum OrderStatus: string {
		case PENDING    = 'pending';
		case WAITING    = 'waiting';
		case PROCESSING = 'processing';
		case COMPLETED  = 'completed';
		case CANCELLED  = 'cancelled';

		public function getShortName(): string {
			return match ($this->value) {
				self::PENDING->value    => 'Neu',
				self::PROCESSING->value => 'In Bearbeitung',
				self::WAITING->value    => 'Wartend',
				self::COMPLETED->value  => 'Abgeschlossen',
				self::CANCELLED->value  => 'Storniert',
			};
		}

		public function getSortIndex(): int {
			return match ($this->value) {
				self::PENDING->value    => 1,
				self::WAITING->value    => 2,
				self::PROCESSING->value => 3,
				self::COMPLETED->value  => 4,
				self::CANCELLED->value  => 5,
			};
		}

		public function orderClosed(): bool {
			return $this == self::CANCELLED || $this == self::COMPLETED;
		}
	}

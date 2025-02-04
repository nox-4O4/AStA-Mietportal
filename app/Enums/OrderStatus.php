<?php

	namespace App\Enums;

	enum OrderStatus: string {
		// order of cases is used for sorting in order list table
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
	}

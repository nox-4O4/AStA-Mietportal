<?php

	namespace App\Enums;

	enum UserRole: int {
		/**
		 * An operator can edit products and orders.
		 */
		case OPERATOR = 1;

		/**
		 * An administrator can also edit users.
		 */
		case ADMIN = 2;

		// TODO migrate this to attributes as soon as there are more roles
		public function getDescription(): string {
			return match ($this->value) {
				self::OPERATOR->value => 'Mitarbeiter',
				self::ADMIN->value    => 'Administrator',
			};
		}

		public function getExplanation(): string {
			return match ($this->value) {
				self::OPERATOR->value => 'Mitarbeiter können Bestellungen und Artikel verwalten sowie Einstellungen vornehmen.',
				self::ADMIN->value    => 'Administratoren können zusätzlich Benutzer verwalten.',
			};
		}
	}

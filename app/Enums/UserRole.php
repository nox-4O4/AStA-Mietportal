<?php

	namespace App\Enums;

	use ReflectionEnumBackedCase;

	enum UserRole: int {
		// order in which enums are defined here is also used in frontend

		/**
		 * An assistant can edit orders.
		 */
		#[RoleAttribute(
			name: 'Helfer',
			explanation: 'Helfer können Bestellungen verwalten.',
			capabilities: ['manage-orders'],
		)]
		case ASSISTANT = 3;

		/**
		 * An operator can edit products, orders, and settings.
		 */
		#[RoleAttribute(
			name: 'Mitarbeiter',
			explanation: 'Mitarbeiter können Bestellungen und Artikel verwalten sowie Einstellungen vornehmen.',
			capabilities: ['manage-orders', 'manage-settings', 'manage-items'],
		)]
		case OPERATOR = 1;

		/**
		 * An administrator can edit everything, including users.
		 */
		#[RoleAttribute(
			name: 'Administrator',
			explanation: 'Administratoren können zusätzlich Benutzer verwalten.',
			capabilities: ['*'],
		)]
		case ADMIN = 2;

		public function getDescription(): string {
			return
				new ReflectionEnumBackedCase(self::class, $this->name)
					->getAttributes(RoleAttribute::class)[0]
					->newInstance()
					->name;
		}

		public function getExplanation(): string {
			return
				new ReflectionEnumBackedCase(self::class, $this->name)
					->getAttributes(RoleAttribute::class)[0]
					->newInstance()
					->explanation;
		}

		public function hasCapability(string $capability): bool {
			$capabilities =
				new ReflectionEnumBackedCase(self::class, $this->name)
					->getAttributes(RoleAttribute::class)[0]
					->newInstance()
					->capabilities;

			return in_array($capability, $capabilities) ||
			       in_array('*', $capabilities);
		}
	}

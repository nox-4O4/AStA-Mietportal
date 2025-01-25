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
	}

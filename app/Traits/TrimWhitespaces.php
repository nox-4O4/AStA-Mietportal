<?php

	namespace App\Traits;

	trait TrimWhitespaces {
		/**
		 * @param string $name
		 * @param mixed  $value
		 */
		public function updatedTrimWhitespaces(string $name, $value): void {
			if(!is_string($value) || in_array($name, $this->trimWhitespacesExcept ?? [])) {
				return;
			}

			$value = trim($value);

			data_set($this, $name, $value);
		}
	}

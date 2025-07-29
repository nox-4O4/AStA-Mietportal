<?php

	namespace App\Traits;

	use TypeError;

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

			if($value === '') {
				try {
					data_set($this, $name, null);
					return;
				} catch(TypeError) {
				}
			}

			data_set($this, $name, $value);
		}
	}

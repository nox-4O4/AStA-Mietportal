<?php

	namespace App\Providers;

	use Illuminate\Support\ServiceProvider;
	use Illuminate\Validation\Rules\Password;
	use Livewire\Livewire;

	class AppServiceProvider extends ServiceProvider {
		/**
		 * Register any application services.
		 */
		public function register(): void {
			//
		}

		/**
		 * Bootstrap any application services.
		 */
		public function boot(): void {
			Livewire::forceAssetInjection(); // TODO remove once login and password reset pages are livewire components

			Password::defaults(Password::default()->rules('not_regex:/asta/i'));
		}
	}

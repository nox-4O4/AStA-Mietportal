<?php

	namespace App\Providers;

	use Auth;
	use Illuminate\Support\ServiceProvider;
	use Illuminate\Validation\Rules\Password;

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
			Auth::provider('app', fn($app, array $config) => $app->make(UserProvider::class, ['model' => $config['model']]));
			Password::defaults(Password::default()->rules('not_regex:/asta/i'));
		}
	}

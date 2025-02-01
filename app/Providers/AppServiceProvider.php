<?php

	namespace App\Providers;

	use App\Enums\UserRole;
	use App\Models\User;
	use Auth;
	use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\Facades\Gate;
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
			// register custom auth provider
			Auth::provider('app', fn($app, array $config) => $app->make(UserProvider::class, ['model' => $config['model']]));

			// adjust password defaults
			Password::defaults(Password::default()->rules('not_regex:/asta/i'));

			// register auth policies and gates
			Gate::define('manage-users', fn(User $user) => $user->role == UserRole::ADMIN);

			// default routes for authenticated / unauthenticated requests
			RedirectIfAuthenticated::redirectUsing(fn() => route(config('app.dashboard.defaultRoute')));

			Blade::directive('money', fn($expression) => <<<php
				<?=number_format(\$x=($expression), fmod(\$x, 1) ? 2 : 0, ',', '.') . "\u{202F}€"?>
				php
			);
		}
	}

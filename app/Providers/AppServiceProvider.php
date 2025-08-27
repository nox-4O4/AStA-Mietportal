<?php

	namespace App\Providers;

	use App\Contracts\PriceCalculation;
	use App\Models\User;
	use App\Util\DTOSynth;
	use App\Util\Markdown;
	use Auth;
	use Carbon\CarbonImmutable;
	use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
	use Illuminate\Foundation\Application;
	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\Facades\Date;
	use Illuminate\Support\Facades\Gate;
	use Illuminate\Support\ServiceProvider;
	use Illuminate\Validation\Rules\Password;
	use IntlDateFormatter;
	use IntlDatePatternGenerator;
	use League\Config\Exception\InvalidConfigurationException;
	use Livewire\Livewire;

	class AppServiceProvider extends ServiceProvider {
		/**
		 * Register any application services.
		 */
		public function register(): void {
			$this->app->bind(PriceCalculation::class, function (Application $app): PriceCalculation {
				/** @var array{class: class-string<PriceCalculation>, configuration: ?array} $config */
				$config = config('shop.price_calculation_providers.' . config('shop.price_calculation'));

				if(!isset($config['class']) || !is_a($config['class'], PriceCalculation::class, true))
					throw new InvalidConfigurationException('configuration shop.price_calculation must be a valid price calculation provider.');

				return $app->make($config['class'], ['configuration' => $config['configuration'] ?? []]);
			});
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
			Gate::define('manage-users', fn(User $user) => $user->role->hasCapability('manage-users'));
			Gate::define('manage-items', fn(User $user) => $user->role->hasCapability('manage-items'));
			Gate::define('manage-settings', fn(User $user) => $user->role->hasCapability('manage-settings'));
			Gate::define('manage-orders', fn(User $user) => $user->role->hasCapability('manage-orders'));

			// default routes for authenticated / unauthenticated requests
			RedirectIfAuthenticated::redirectUsing(fn() => route(config('shop.dashboard.defaultRoute')));

			Livewire::propertySynthesizer(DTOSynth::class);

			// some blade helpers
			Blade::directive('money', fn($expression) => <<<PHP
				<?=number_format(\$x=(round($expression, 2)), fmod(\$x, 1) ? 2 : 0, ',', '.') . "\u{202F}€"?>
				PHP
			);

			Blade::directive('content', fn($expression) => <<<PHP
				<?=\App\Models\Content::fromName($expression)?->render() ?? '<i>Inhalt noch nicht verfügbar.</i>'?>
				PHP
			);

			Blade::directive('trim', fn() => '<?php ob_start(); ?>');
			Blade::directive('endtrim', fn() => '<?php echo trim(ob_get_clean()); ?>');

			Blade::stringable(fn(CarbonImmutable $dateTime) => $dateTime->formatLocalDate());
			Blade::stringable(fn(Markdown $markdown) => $markdown->render());

			Date::use(CarbonImmutable::class);
			CarbonImmutable::macro('formatLocalDate', static function (): string {
				/**
				 * @noinspection PhpUndefinedMethodInspection it's defined in carbon macro context
				 * @var CarbonImmutable $date
				 */
				$date    = self::this();
				$locale  = config('app.locale');
				$pattern = IntlDatePatternGenerator::create($locale)->getBestPattern('ddMMyyyy');

				return IntlDateFormatter::create($locale, pattern: $pattern)->format($date);
			});

			CarbonImmutable::macro('formatLocalTime', static function (bool $seconds = true): string {
				/**
				 * @noinspection PhpUndefinedMethodInspection it's defined in carbon macro context
				 * @var CarbonImmutable $date
				 */
				$date    = self::this();
				$locale  = config('app.locale');
				$pattern = IntlDatePatternGenerator::create($locale)->getBestPattern('jjmm' . ($seconds ? 'ss' : ''));

				return IntlDateFormatter::create($locale, pattern: $pattern)->format($date);
			});

			// TODO when on Laravel 12+ test and use this to automatically remove cached font files:
			/*
			\Illuminate\Support\Facades\Event::listen(function (Illuminate\Cache\Events\CacheFlushing $event) {
				foreach(glob(storage_path('framework/cache/fonts/')) as $file)
					unlink($file);
			});
			*/
		}
	}

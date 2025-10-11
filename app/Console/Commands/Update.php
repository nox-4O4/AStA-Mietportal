<?php

	namespace App\Console\Commands;

	use Illuminate\Console\Command;

	class Update extends Command {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'update {--force}';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Performs necessary update steps after new code has been checked out';

		/**
		 * Execute the console command.
		 */
		public function handle(): void {
			if(!$this->option('force')) {
				$this->line(<<<EOF
					This command will run the following tasks:
					    - artisan down                        (enable maintenance mode)
					    - composer install                    (install PHP packages)
					    - npm install                         (install node packages)
					    - npm run build                       (generate static assets)
					    - artisan migrate --seed --force      (runs new database migrations)
					    - artisan cache:clear                 (clears the Laravel cache)
					    - artisan optimize:clear              (clears optimized configuration)
					    - artisan optimize --except config    (application performance optimizations)
					    - artisan up                          (disable maintenance mode)
					EOF
				);

				$response = $this->askWithCompletion('Do you want to continue? (yes, no)', ['yes', 'no'], 'no');
				if($response != 'yes' && $response != 'y') {
					$this->line('Execution cancelled.');
					return;
				}
			}

			$this->call('down');
			exec('composer install');
			exec('npm install');
			exec('npm run build');
			$this->call('migrate', ['--seed' => true, '--force' => true]);
			$this->call('cache:clear');
			$this->call('optimize:clear');
			$this->call('optimize', ['--except' => 'config']);
			$this->call('up');
		}
	}

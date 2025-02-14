<?php

	return [

		/*
		|--------------------------------------------------------------------------
		| Default Database Connection Name
		|--------------------------------------------------------------------------
		|
		| Here you may specify which of the database connections below you wish
		| to use as your default connection for database operations. This is
		| the connection which will be utilized unless another connection
		| is explicitly specified when you execute a query / statement.
		|
		*/

		'default' => env('DB_CONNECTION', 'mysql'),

		/*
		|--------------------------------------------------------------------------
		| Database Connections
		|--------------------------------------------------------------------------
		|
		| Below are all of the database connections defined for your application.
		| An example configuration is provided for each database system which
		| is supported by Laravel. You're free to add / remove connections.
		|
		*/

		'connections' => [

			'mysql' => [
				'driver'         => env('DB_DRIVER', 'mysql'),
				'url'            => env('DB_URL'),
				'host'           => env('DB_HOST', '127.0.0.1'),
				'port'           => env('DB_PORT', '3306'),
				'database'       => env('DB_DATABASE', 'asta'),
				'username'       => env('DB_USERNAME', 'asta'),
				'password'       => env('DB_PASSWORD', ''),
				'unix_socket'    => env('DB_SOCKET', ''),
				'charset'        => env('DB_CHARSET', 'utf8mb4'),
				'collation'      => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
				'prefix'         => '',
				'prefix_indexes' => true,
				'strict'         => true,
				'engine'         => null,
				/* The following 'modes' definition matches the default value without 'ONLY_FULL_GROUP_BY'. We're not specifying ONLY_FULL_GROUP_BY as MariaDB lacks real support
				 * for functional dependency (see MDEV-11588). MySQL also does not correctly evaluate functional dependency when dependency is guaranteed by partition-clause of
				 * window function, leading to cumbersome workarounds in SQL queries. */
				'modes'          => ['STRICT_TRANS_TABLES', 'NO_ZERO_IN_DATE', 'NO_ZERO_DATE', 'ERROR_FOR_DIVISION_BY_ZERO', 'NO_ENGINE_SUBSTITUTION'],
				'options'        => extension_loaded('pdo_mysql')
					? array_filter([PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA')])
					: [],
			],

		],

		/*
		|--------------------------------------------------------------------------
		| Migration Repository Table
		|--------------------------------------------------------------------------
		|
		| This table keeps track of all the migrations that have already run for
		| your application. Using this information, we can determine which of
		| the migrations on disk haven't actually been run on the database.
		|
		*/

		'migrations' => [
			'table'                  => 'migrations',
			'update_date_on_publish' => true,
		],

	];

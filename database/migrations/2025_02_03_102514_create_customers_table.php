<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('customers', function (Blueprint $table) {
				$table->id();
				$table->string('forename');
				$table->string('surname');
				$table->string('legalname')->nullable();
				$table->string('street')->nullable();
				$table->string('number')->nullable();
				$table->string('zipcode')->nullable();
				$table->string('city')->nullable();
				$table->string('email');
				$table->string('mobile');
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('customers');
		}
	};

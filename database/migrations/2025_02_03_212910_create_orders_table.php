<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('orders', function (Blueprint $table) {
				$table->id();
				$table->enum('status', ['pending', 'processing', 'waiting', 'completed', 'cancelled'])->default('pending');
				$table->decimal('rate', places: 4)->default(1);
				$table->string('event_name');
				$table->string('note', 4096);
				$table->foreignId('customer_id')
				      ->constrained('customers')
				      ->cascadeOnUpdate()
				      ->cascadeOnDelete();
				$table->decimal('deposit')->default(0);
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('orders');
		}
	};

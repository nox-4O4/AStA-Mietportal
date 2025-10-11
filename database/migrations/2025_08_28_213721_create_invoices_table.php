<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('invoices', function (Blueprint $table) {
				$table->id();
				$table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnUpdate()->nullOnDelete();
				$table->bigInteger('number')->unsigned();
				$table->integer('version')->unsigned();
				$table->foreignId('customer_id')
				      ->constrained('customers')
				      ->cascadeOnUpdate()
				      ->restrictOnDelete();
				$table->boolean('notified')->default(false);
				$table->boolean('cancelled')->default(false);
				$table->boolean('cancellation_notified')->default(false);
				$table->char('content_hash', 40); // 40 chars for sha1 hashes
				$table->decimal('total_amount');
				$table->timestamps();
				$table->unique(['number', 'version']);
			});

			Schema::table('orders', function (Blueprint $table) {
				$table->boolean('invoice_required')
				      ->default(false)
				      ->after('deposit');
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('invoices');
			Schema::table('orders', function (Blueprint $table) {
				$table->dropColumn('invoice_required');
			});
		}
	};

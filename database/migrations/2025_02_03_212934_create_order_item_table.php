<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('order_item', function (Blueprint $table) {
				$table->id();
				$table->foreignId('order_id')->constrained('orders')->cascadeOnUpdate()->cascadeOnDelete();
				$table->foreignId('item_id')->constrained('items')->cascadeOnUpdate()->cascadeOnDelete();
				$table->integer('quantity')->unsigned();
				$table->date('start');
				$table->date('end');
				$table->decimal('original_price');
				$table->decimal('price');
				$table->string('comment', 4096);
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('order_item');
		}
	};

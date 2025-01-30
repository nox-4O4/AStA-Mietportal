<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('item_groups', function (Blueprint $table) {
				$table->id();
				$table->string('name', 4096);
				$table->text('description')->nullable();
				$table->timestamps();
			});

			Schema::create('items', function (Blueprint $table) {
				$table->id();
				$table->string('name', 4096);
				$table->text('description');
				$table->integer('amount')->unsigned();
				$table->boolean('available');
				$table->boolean('visible');
				$table->decimal('price');
				$table->decimal('deposit');
				$table->foreignId('item_group_id')
				      ->nullable()
				      ->constrained()
				      ->cascadeOnUpdate()
				      ->nullOnDelete();
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('items');
			Schema::dropIfExists('item_groups');
		}
	};

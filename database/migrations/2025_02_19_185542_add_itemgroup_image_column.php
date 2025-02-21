<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::table('item_groups', function (Blueprint $table) {
				$table->foreignId('image_id')
				      ->nullable()
				      ->after('description')
				      ->constrained('images')
				      ->cascadeOnUpdate()
				      ->nullOnDelete();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::table('item_groups', function (Blueprint $table) {
				$table->dropConstrainedForeignId('image_id');
			});
		}
	};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('coupons', function (Blueprint $table) {
			$table->id();
			$table->string('name', 100);
			$table->string('code', 25);
			$table->integer('amount');
			$table->string('type', 15);
			$table->json('products')->nullable();
			$table->smallInteger('limit')->nullable();
			$table->smallInteger('usage')->nullable();
			$table->smallInteger('auto_apply')->nullable();
			$table->date('published_from')->nullable();
			$table->date('published_to')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('coupons');
	}
};

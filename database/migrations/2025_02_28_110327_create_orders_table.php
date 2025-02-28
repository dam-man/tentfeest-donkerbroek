<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id');
			$table->string('payment_id', 40)->nullable();
			$table->unsignedBigInteger('coupon_id')->nullable();
			$table->integer('discount')->nullable();
			$table->integer('total')->nullable();
			$table->integer('amount')->nullable();
			$table->string('status')->default('open');
			$table->smallInteger('completed')->nullable();
            $table->timestamps();
			$table->softDeletes();

	        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

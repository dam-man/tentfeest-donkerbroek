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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
			$table->string('token', 25)->unique()->nullable();
			$table->string('payment_id', 40)->index()->nullable();
	        $table->unsignedBigInteger('order_id')->nullable();
			$table->string('iban', 40)->nullable();
			$table->integer('amount');
			$table->string('status', 25)->default('open');
            $table->timestamps();

	        $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};

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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
	        $table->unsignedBigInteger('user_id')->nullable();
	        $table->unsignedBigInteger('order_id')->nullable();
			$table->string('message_id', 255);
			$table->string('subject', 255);
	        $table->smallInteger('has_attachment')->default(0);
			$table->dateTime('delivered')->nullable();
			$table->dateTime('failed')->nullable();
			$table->dateTime('opened')->nullable();
			$table->dateTime('clicked')->nullable();
			$table->dateTime('unsubscribed')->nullable();
			$table->dateTime('complained')->nullable();
            $table->timestamps();
			$table->softDeletes();

	        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};

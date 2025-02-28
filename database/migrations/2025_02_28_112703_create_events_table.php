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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->text('description')->nullable();
	        $table->string('image')->nullable();
			$table->text('bullets')->nullable();
	        $table->string('type', 25)->default('toegangskaart');
	        $table->string('pdf_source', 50)->nullable();
	        $table->string('pdf_orientation', 15)->default('landscape');
	        $table->string('pdf_format', 5)->default('A5');
	        $table->date('date')->nullable();
	        $table->integer('price')->default(0);
	        $table->integer('available')->default(2000);
	        $table->integer('sold')->default(0);
			$table->smallInteger('published')->default(0);
			$table->dateTime('published_at')->nullable();
			$table->dateTime('unpublish_at')->nullable();
	        $table->unsignedBigInteger('created_by')->nullable();
	        $table->unsignedBigInteger('updated_by')->nullable();
	        $table->unsignedBigInteger('published_by')->nullable();
            $table->timestamps();
			$table->softDeletes();

	        $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

	    Schema::create('event_order', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('user_id')->nullable();
		    $table->unsignedBigInteger('order_id')->nullable();
		    $table->unsignedBigInteger('event_id')->nullable();
		    $table->tinyInteger('quantity')->nullable();
		    $table->string('barcode', 50)->index()->nullable();
		    $table->tinyInteger('scanned')->default(0);
			$table->dateTime('scanned_at')->nullable();
		    $table->unsignedBigInteger('scanned_by')->nullable();

		    $table->foreign('scanned_by')->references('id')->on('users')->onDelete('set null');
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
		    $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
		    $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('medium', 20)->nullable();
			$table->integer('clicks')->default(0);
			$table->string('token',50)->nullable();
	        $table->unsignedBigInteger('created_by')->nullable();
	        $table->unsignedBigInteger('updated_by')->nullable();
	        $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
			$table->softDeletes();

	        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};

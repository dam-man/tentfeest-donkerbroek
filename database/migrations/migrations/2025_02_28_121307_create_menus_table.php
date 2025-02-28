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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
			$table->string('name', 191);
	        $table->string('icon')->nullable();
			$table->string('route', 191)->nullable();
			$table->string('role', 20);
	        $table->string('type')->nullable();
			$table->integer('ordering')->nullable();
			$table->date('publish_at')->nullable();
			$table->date('unpublish_at')->nullable();
			$table->smallInteger('published')->default(0);
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

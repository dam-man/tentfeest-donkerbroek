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
		Schema::disableForeignKeyConstraints();

		Schema::dropIfExists('menu_permission');
		Schema::dropIfExists('permission_role');
		Schema::dropIfExists('permission_user');
		Schema::dropIfExists('permissions');

		Schema::dropIfExists('role_user');
		Schema::dropIfExists('roles');

		Schema::enableForeignKeyConstraints();
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		// No need to add them again.
	}
};

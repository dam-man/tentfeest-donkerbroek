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
		if(!Schema::hasColumn('menus', 'type'))
		{
			Schema::table('menus', function (Blueprint $table) {
				$table->string('type')->nullable()->after('route');
			});
		}

	    if(!Schema::hasColumn('menus', 'icon'))
	    {
		    Schema::table('menus', function (Blueprint $table) {
			    $table->string('icon')->nullable()->after('name');
		    });
	    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    if(Schema::hasColumn('menus', 'type'))
	    {
		    Schema::table('menus', function (Blueprint $table) {
			    $table->dropColumn('type');
		    });
	    }
	    if(Schema::hasColumn('menus', 'icon'))
	    {
		    Schema::table('menus', function (Blueprint $table) {
			    $table->dropColumn('icon');
		    });
	    }
    }
};

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//Add columns to Users
		Schema::table('users', function($table)
		{
		    $table->string('username')->unique()->after('email');
		    $table->string('photo')->nullable();
		    $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Update the users table
		Schema::table('users', function($table)
		{
			$table->dropColumn('username', 'photo', 'deleted_at');
		});
	}

}
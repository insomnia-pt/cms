<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagestypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages_types', function($table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->text('config')->nullable();
			$table->string('controller')->nullable();
			$table->integer('system')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages_types');
	}

}
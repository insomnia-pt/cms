<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function($table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id')->unsigned();
			$table->string('name')->nullable();
			$table->string('icon')->nullable();
			$table->string('url')->nullable();
			$table->integer('id_parent')->unsigned()->default(0);
			$table->integer('datasource_id')->unsigned()->nullable();
			$table->integer('order')->unsigned();
			$table->integer('visible')->unsigned()->default(0);
			$table->integer('system')->unsigned()->default(0);
			$table->integer('group_id')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('menus');
	}

}
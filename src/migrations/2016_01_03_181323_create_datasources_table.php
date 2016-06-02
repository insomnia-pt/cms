<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datasources', function($table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id')->unsigned();
			$table->string('name')->nullable();
			$table->text('description')->nullable();
			$table->string('table')->nullable();
			$table->text('config')->nullable();
			$table->text('options')->nullable();
			$table->integer('system')->unsigned()->nullable()->default(0);
			$table->integer('menu')->unsigned()->nullable()->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datasources');
	}

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasourcesfieldtypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datasources_fieldtypes', function($table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('type');
			$table->text('config');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datasources_fieldtypes');
	}

}
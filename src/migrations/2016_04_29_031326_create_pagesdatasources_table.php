<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesdatasourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('datasource_page', function($table)
		{
			$table->engine = 'InnoDB';

			$table->integer('page_id')->unsigned();
			$table->integer('datasource_id')->unsigned();

			$table->primary(array('page_id', 'datasource_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('datasource_page');
	}

}
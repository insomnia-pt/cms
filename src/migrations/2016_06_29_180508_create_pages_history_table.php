<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages_history', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('title');
			$table->string('slug');
			$table->text('content');
			$table->integer('page_id')->unsigned();
			$table->integer('page_type')->unsigned();
			$table->integer('user_id')->unsigned();
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
		Schema::drop('pages_history');
	}

}

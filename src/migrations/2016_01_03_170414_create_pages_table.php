<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('title');
			$table->string('slug');
			$table->mediumText('content');
			$table->text('meta')->nullable();
			$table->integer('id_parent')->unsigned()->default(0);
			$table->integer('system')->unsigned();
			$table->integer('visible')->unsigned()->default(1);
			$table->integer('editable')->unsigned()->default(1);
			$table->integer('order')->unsigned();
			$table->integer('pagetype_id')->unsigned();
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
		Schema::drop('pages');
	}

}

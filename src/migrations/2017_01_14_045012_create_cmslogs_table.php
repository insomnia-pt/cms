<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmslogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmslogs', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('action')->nullable();
            $table->text('data')->nullable();
            $table->string('module')->nullable();
            $table->integer('entry_id')->unsigned()->nullable();
            $table->integer('datasource_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
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
        Schema::drop('cmslogs');
    }

}

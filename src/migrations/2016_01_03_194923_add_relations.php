<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pages', function($table){
		    $table->foreign('pagetype_id')->references('id')->on('pages_types');
		});

		Schema::table('menus_items', function($table){
		    $table->foreign('menu_id')->references('id')->on('menus');
		    $table->foreign('datasource_id')->references('id')->on('datasources');
		});

		Schema::table('datasources_relations', function($table){
		    $table->foreign('datasource_id')->references('id')->on('datasources');
		    $table->foreign('relation_datasource_id')->references('id')->on('datasources');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pages', function($table){
		    $table->dropForeign('pages_pagetype_id_foreign');
		});

		Schema::table('menus_items', function($table){
		    $table->dropForeign('menus_menu_id_foreign');
		    $table->dropForeign('menus_datasource_id_foreign');
		});

		Schema::table('datasources_relations', function($table){
		    $table->dropForeign('datasources_relations_datasource_id_foreign');
		    $table->dropForeign('datasources_relations_relation_datasource_id_foreign');
		});
	}

}
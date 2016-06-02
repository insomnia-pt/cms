<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\Datasource as Datasource;

class DatasourceTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('datasources')->truncate();

        $datasource = new Datasource;
        $datasource->name = 'Páginas';
        $datasource->table = 'pages';
        $datasource->config = '[{"description":"Título","datatype":"2","show_in_table":1,"name":"title"},{"description":"Slug","datatype":"2","show_in_table":0,"name":"slug"}]';
        $datasource->options = '{"subitems":1, "permissions": ["view", "create", "update", "delete"]}';
        $datasource->system = 1;
        $datasource->menu = 1;
        $datasource->save();

    }

}
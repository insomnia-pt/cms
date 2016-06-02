<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\PageType as PageType;

class PagetypeTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('pages_types')->truncate();

        $pagetype = new PageType;
        $pagetype->id = 1;
        $pagetype->name = 'Básica';
        $pagetype->config = '{"areas":[{"name":"area1", "field": {"name":"Conteúdo","description":"", "datatype":5, "size": 10 } }]}';
        $pagetype->controller = 'BasicController';
        $pagetype->system = 0;
        $pagetype->save();

        $pagetype = new PageType;
        $pagetype->id = 2;
        $pagetype->name = 'Home';
        $pagetype->config = '{"areas":[{"name":"intro", "field": {"name":"Conteúdo","description":"", "datatype":5, "size": 10 } }]}';
        $pagetype->controller = 'HomeController';
        $pagetype->system = 1;
        $pagetype->save();

    }

}
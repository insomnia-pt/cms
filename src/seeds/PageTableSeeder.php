<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\Page as Page;

class PageTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('pages')->truncate();

        $page = new Page;
        $page->title = 'Home';
        $page->slug = '/';
        $page->content = '{"intro":"Lorem ipsum"}';
        $page->language = 'pt';
        $page->order = 1;
        $page->system = 1;
        $page->pagetype_id = 2;
        $page->save();

    }

}
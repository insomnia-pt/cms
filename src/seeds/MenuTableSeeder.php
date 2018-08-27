<?php namespace Insomnia\Cms\Seeds;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\Menu as Menu;

class MenuTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('menus')->truncate();

        $menu = new Menu;
        $menu->id = 1;
        $menu->name = 'Default';
        $menu->system = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id = 2;
        $menu->name = 'Administrador';
        $menu->system = 1;
        $menu->save();

        $menu = new Menu;
        $menu->id = 3;
        $menu->name = 'Editor';
        $menu->system = 1;
        $menu->save();
        
    }

}
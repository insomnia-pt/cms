<?php namespace Insomnia\Cms\Seeds;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\MenuItem as MenuItem;

class MenuitemTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('menus_items')->truncate();

        $menu = new MenuItem;
        $menu->id = 1;
        $menu->name = 'Dashboard';
        $menu->icon = 'fa-dashboard';
        $menu->url = '/dashboard';
        $menu->order = 1;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 1;
        $menu->save();

        $menu = new MenuItem;
        $menu->id = 2;
        $menu->name = 'Páginas';
        $menu->icon = 'fa-file-text';
        $menu->url = '/pages';
        $menu->order = 2;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 1;
        $menu->save();

        $menu = new MenuItem;
        $menu->id = 3;
        $menu->name = 'Acessos';
        $menu->icon = 'fa-user';
        $menu->order = 3;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 1;
        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 4;
	        $menu->name = 'Grupos';
	        $menu->url = '/groups';
	        $menu->id_parent = 3;
	        $menu->order = 1;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 1;
	        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 5;
	        $menu->name = 'Utilizadores';
	        $menu->url = '/users';
	        $menu->id_parent = 3;
	        $menu->order = 2;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 1;
	        $menu->save();

	    $menu = new MenuItem;
	    $menu->id = 6;
        $menu->name = 'Configurações';
        $menu->icon = 'fa-gear';
        $menu->order = 4;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 1;
        $menu->save();

       	$menu = new MenuItem;
       	$menu->id = 7;
	        $menu->name = 'Menus';
	        $menu->url = '/menus';
	        $menu->id_parent = 6;
	        $menu->order = 1;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 1;
	        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 8;
	        $menu->name = 'Data Sources';
	        $menu->url = '/datasources';
	        $menu->id_parent = 6;
	        $menu->order = 2;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 1;
	        $menu->save();

	    $menu = new MenuItem;
	    $menu->id = 9;
        $menu->name = 'Gestor de Ficheiros';
        $menu->url = '/filebrowser';
        $menu->icon = 'fa-folder';
        $menu->order = 5;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 1;
        $menu->save();


		// Administradores
	    $menu = new MenuItem;
        $menu->id = 10;
        $menu->name = 'Dashboard';
        $menu->icon = 'fa-dashboard';
        $menu->url = '/dashboard';
        $menu->order = 1;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 2;
        $menu->save();

        $menu = new MenuItem;
        $menu->id = 11;
        $menu->name = 'Páginas';
        $menu->icon = 'fa-file-text';
        $menu->url = '/pages';
        $menu->order = 2;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 2;
        $menu->save();

        $menu = new MenuItem;
        $menu->id = 12;
        $menu->name = 'Acessos';
        $menu->icon = 'fa-user';
        $menu->order = 3;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 2;
        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 13;
	        $menu->name = 'Grupos';
	        $menu->url = '/groups';
	        $menu->id_parent = 12;
	        $menu->order = 1;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 2;
	        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 14;
	        $menu->name = 'Utilizadores';
	        $menu->url = '/users';
	        $menu->id_parent = 12;
	        $menu->order = 2;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 2;
	        $menu->save();

	    $menu = new MenuItem;
	    $menu->id = 15;
        $menu->name = 'Configurações';
        $menu->icon = 'fa-gear';
        $menu->order = 4;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 2;
        $menu->save();

       	$menu = new MenuItem;
       	$menu->id = 16;
	        $menu->name = 'Menus';
	        $menu->url = '/menus';
	        $menu->id_parent = 15;
	        $menu->order = 1;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 2;
	        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 17;
	        $menu->name = 'Data Sources';
	        $menu->url = '/datasources';
	        $menu->id_parent = 15;
	        $menu->order = 2;
	        $menu->visible = 1;
	        $menu->system = 1;
	        $menu->menu_id = 2;
	        $menu->save();

	    $menu = new MenuItem;
	    $menu->id = 18;
        $menu->name = 'Gestor de Ficheiros';
        $menu->url = '/filebrowser';
        $menu->icon = 'fa-folder';
        $menu->order = 5;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 2;
        $menu->save();


		// Editores
	    $menu = new MenuItem;
        $menu->id = 19;
        $menu->name = 'Dashboard';
        $menu->icon = 'fa-dashboard';
        $menu->url = '/dashboard';
        $menu->order = 1;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 3;
        $menu->save();

        $menu = new MenuItem;
        $menu->id = 20;
        $menu->name = 'Páginas';
        $menu->icon = 'fa-file-text';
        $menu->url = '/pages';
        $menu->order = 2;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 3;
        $menu->save();

        $menu = new MenuItem;
        $menu->id = 21;
        $menu->name = 'Acessos';
        $menu->icon = 'fa-user';
        $menu->order = 3;
        $menu->visible = 0;
        $menu->system = 1;
        $menu->menu_id = 3;
        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 22;
	        $menu->name = 'Grupos';
	        $menu->url = '/groups';
	        $menu->id_parent = 21;
	        $menu->order = 1;
	        $menu->visible = 0;
	        $menu->system = 1;
	        $menu->menu_id = 3;
	        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 23;
	        $menu->name = 'Utilizadores';
	        $menu->url = '/users';
	        $menu->id_parent = 21;
	        $menu->order = 2;
	        $menu->visible = 0;
	        $menu->system = 1;
	        $menu->menu_id = 3;
	        $menu->save();

	    $menu = new MenuItem;
	    $menu->id = 24;
        $menu->name = 'Configurações';
        $menu->icon = 'fa-gear';
        $menu->order = 4;
        $menu->visible = 0;
        $menu->system = 1;
        $menu->menu_id = 3;
        $menu->save();

//        	$menu = new MenuItem;
//        	$menu->id = 25;
//	        $menu->name = 'Menu';
//	        $menu->url = '/menu';
//	        $menu->id_parent = 24;
//	        $menu->order = 1;
//	        $menu->visible = 1;
//	        $menu->system = 1;
//	        $menu->menu_id = 2;
//	        $menu->save();

	        $menu = new MenuItem;
	        $menu->id = 26;
	        $menu->name = 'Data Sources';
	        $menu->url = '/datasources';
	        $menu->id_parent = 24;
	        $menu->order = 2;
	        $menu->visible = 0;
	        $menu->system = 1;
	        $menu->menu_id = 3;
	        $menu->save();

	    $menu = new MenuItem;
	    $menu->id = 27;
        $menu->name = 'Gestor de Ficheiros';
        $menu->url = '/filebrowser';
        $menu->icon = 'fa-folder';
        $menu->order = 5;
        $menu->visible = 1;
        $menu->system = 1;
        $menu->menu_id = 3;
        $menu->save();

    }

}
<?php namespace Insomnia\Cms;

use Seeder;
use Eloquent;
use Db;
use Insomnia\Cms\Models\Group as Group;

class GroupTableSeeder extends Seeder {

	public function run()
    {
        \DB::table('groups')->truncate();

        $group = new Group;
        $group->name = 'Administradores';
        $group->permissions = ['admin'=>1, 'cms'=>1, 'groups.view'=>1, 'groups.create'=>1, 'groups.update'=>1, 'groups.delete'=>1, 'users.view'=>1, 'users.create'=>1, 'users.update'=>1, 'users.delete'=>1, 'users.group'=> 1, 'pages.view'=>1, 'pages.create'=>1, 'pages.update'=>1, 'pages.delete'=>1, 'datasources.view'=>1, 'datasources.create'=>1, 'datasources.update'=>1, 'datasources.delete'=>1, 'menus.view'=>1, 'menus.create'=>1, 'menus.update'=>1, 'menus.delete'=>1, 'filebrowser.view'=>1, 'display.menu.2' => 1];
        $group->save();

        $group = new Group;
        $group->name = 'Editores';
        $group->permissions = ['cms'=>1, 'pages.view'=>1, 'pages.create'=>1, 'pages.update'=>1, 'pages.delete'=>1, 'filebrowser.view'=>1, 'display.menu.3' => 1];
        $group->save();

    }

}
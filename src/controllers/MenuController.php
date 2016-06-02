<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Input;
use Lang;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;
use URL;
use Helpers;
use Session;
use Response;
use Menu;
use Datasource;
use Artisan;


class MenuController extends AdminController {

	public function getIndex($groupId=null)
	{	
		AdminController::checkPermission('menu.view');

		$groups = Sentry::getGroupProvider()->createModel()->paginate();
		if(!$groupId){ $groupId = $groups[0]->id; }

		$menulist = Menu::where('id_parent', 0)->where('visible', 1)->where('group_id', $groupId)->orderBy('order')->get();
		$menuoutlist = Menu::where('id_parent', 0)->where('visible', 0)->where('group_id', $groupId)->orderBy('order')->get();
		$menulistdatasources = Menu::where('datasource_id', '!=', 'null')->where('group_id', $groupId)->lists('datasource_id');

		$datasourcelist = [];
		if(count($menulistdatasources)) {
			$datasourcelist = Datasource::whereNotIn('id', $menulistdatasources)->where('system', 0)->get();
		} else {
			$datasourcelist = Datasource::where('system', 0)->get();
		}

		$allmenuoutlist = $menuoutlist->merge($datasourcelist);
		$allmenuoutlist->all();

		

		return View::make('ocms::menu/index', compact('menulist','allmenuoutlist','groups','groupId'));
	}

	public function postEdit($groupId=null)
	{
		AdminController::checkPermission('menu.update');

		$groups = Sentry::getGroupProvider()->createModel()->paginate();
		if(!$groupId){ $groupId = $groups[0]->id; }

		$inputs = Input::except('_token');
		
		$menulist = json_decode(Input::get('menuconfig'));
		$menuoutlist = json_decode(Input::get('menuoutconfig'));

		Menu::where('group_id', $groupId)->delete();

		foreach ($menulist as $key => $menuitem) {
			$menu = new Menu;
			$menu->name = $menuitem->name;
			$menu->icon = $menuitem->icon;
			$menu->url = $menuitem->url;
			$menu->id_parent = 0;
			$menu->datasource_id = $menuitem->datasource_id?$menuitem->datasource_id:null;
			$menu->order = $key;
			$menu->visible = 1;
			$menu->system = @$menuitem->system?$menuitem->system:0;
			$menu->group_id = $groupId;
			$menu->save();

			if(count(@$menuitem->children)){
				foreach ($menuitem->children as $keychildren => $menuitemchildren) {
					$menuchildren = new Menu;
					$menuchildren->name = $menuitemchildren->name;
					$menuchildren->icon = $menuitemchildren->icon;
					$menuchildren->url = $menuitemchildren->url;
					$menuchildren->id_parent = $menu->id;
					$menuchildren->datasource_id = $menuitemchildren->datasource_id?$menuitemchildren->datasource_id:null;
					$menuchildren->order = $keychildren;
					$menuchildren->visible = 1;
					$menuchildren->system = @$menuitemchildren->system?$menuitemchildren->system:0;
					$menuchildren->group_id = $groupId;
					$menuchildren->save();
				}
			}
		}

		if($menuoutlist) {
			foreach ($menuoutlist as $key => $menuitem) {
				if(!@$menuitem->datasource_id) {
					$menu = new Menu;
					$menu->name = $menuitem->name;
					$menu->icon = $menuitem->icon;
					$menu->url = $menuitem->url;
					$menu->id_parent = 0;
					$menu->datasource_id = null;
					$menu->order = $key;
					$menu->visible = 0;
					$menu->system = @$menuitem->system?$menuitem->system:0;
					$menu->group_id = $groupId;
					$menu->save();

					if(count(@$menuitem->children)){
						foreach ($menuitem->children as $keychildren => $menuitemchildren) {
							if(!@$menuitemchildren->datasource_id) {
								$menuchildren = new Menu;
								$menuchildren->name = $menuitemchildren->name;
								$menuchildren->icon = $menuitemchildren->icon;
								$menuchildren->url = $menuitemchildren->url;
								$menuchildren->id_parent = $menu->id;
								$menuchildren->datasource_id = null;
								$menuchildren->order = $keychildren;
								$menuchildren->visible = 0;
								$menuchildren->system = @$menuitemchildren->system?$menuitemchildren->system:0;
								$menuchildren->group_id = $groupId;
								$menuchildren->save();
							}
						}
					}
				}
			}
		}

  		$allMenus = Menu::orderBy('order')->get();
  		Menu::truncate();

  		$parentMenus = $allMenus->filter(function($menu) {
		    return $menu->id_parent == 0;
		})->values();

		foreach ($parentMenus as $menuitem) {
			$newMenu = new Menu;
			$newMenu->name = $menuitem->name;
			$newMenu->icon = $menuitem->icon;
			$newMenu->url = $menuitem->url;
			$newMenu->id_parent = 0;
			$newMenu->datasource_id = $menuitem->datasource_id;
			$newMenu->order = $menuitem->order;
			$newMenu->visible = $menuitem->visible;
			$newMenu->system = $menuitem->system;
			$newMenu->group_id = $menuitem->group_id;
			$newMenu->save();

			$subMenus = $allMenus->filter(function($submenu) use ($menuitem) {
			    return $submenu->id_parent == $menuitem->id;
			})->values();

			foreach ($subMenus as $submenuitem) {
				$newSubMenu = new Menu;
				$newSubMenu->name = $submenuitem->name;
				$newSubMenu->icon = $submenuitem->icon;
				$newSubMenu->url = $submenuitem->url;
				$newSubMenu->id_parent = $newMenu->id;
				$newSubMenu->datasource_id = $submenuitem->datasource_id;
				$newSubMenu->order = $submenuitem->order;
				$newSubMenu->visible = $submenuitem->visible;
				$newSubMenu->system = $submenuitem->system;
				$newSubMenu->group_id = $submenuitem->group_id;
				$newSubMenu->save();
				
			}

		}

		return Redirect::back()->with('success', Lang::get('_ocms/messages.success'));
	}

}

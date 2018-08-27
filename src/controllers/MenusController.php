<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\Datasource as Datasource;
use Insomnia\Cms\Models\Menu as Menu;
use Insomnia\Cms\Models\MenuItem as MenuItem;
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
use Artisan;


class MenusController extends AdminController {

	public function getIndex($menuId=null)
	{	
		AdminController::checkPermission('menus.view');

		//except 1 - Default
		$systemMenus = Menu::all()->except(1);
		if(!$menuId){ $menuId = $systemMenus[0]->id; }

		$menulist = MenuItem::where('id_parent', 0)->where('visible', 1)->where('menu_id', $menuId)->orderBy('order')->get();
		$menuoutlist = MenuItem::where('id_parent', 0)->where('visible', 0)->where('menu_id', $menuId)->orderBy('order')->get();
		$menulistdatasources = MenuItem::where('datasource_id', '!=', 'null')->where('menu_id', $menuId)->lists('datasource_id');

		$datasourcelist = [];
		if(count($menulistdatasources)) {
			$datasourcelist = Datasource::whereNotIn('id', $menulistdatasources)->where('system', 0)->get();
		} else {
			$datasourcelist = Datasource::where('system', 0)->get();
		}

		$allmenuoutlist = $menuoutlist->merge($datasourcelist);
		$allmenuoutlist->all();

		

		return View::make('cms::menus/index', compact('menulist','allmenuoutlist','systemMenus','menuId'));
	}

	public function postEdit($menuId=null)
	{
		AdminController::checkPermission('menus.update');

		//except 1 - Default
		$systemMenus = Menu::all()->except(1);
		if(!$menuId){ $menuId = $systemMenus[0]->id; }

		$inputs = Input::except('_token');
		
		$menulist = json_decode(Input::get('menuconfig'));
		$menuoutlist = json_decode(Input::get('menuoutconfig'));

		MenuItem::where('menu_id', $menuId)->delete();

		foreach ($menulist as $key => $menuitem) {
			$menu = new MenuItem;
			$menu->name = $menuitem->name;
			$menu->icon = $menuitem->icon;
			$menu->url = $menuitem->url;
			$menu->id_parent = 0;
			$menu->datasource_id = $menuitem->datasource_id?$menuitem->datasource_id:null;
			$menu->order = $key;
			$menu->visible = 1;
			$menu->system = @$menuitem->system?$menuitem->system:0;
			$menu->menu_id = $menuId;
			$menu->save();

			if(count(@$menuitem->children)){
				foreach ($menuitem->children as $keychildren => $menuitemchildren) {
					$menuchildren = new MenuItem;
					$menuchildren->name = $menuitemchildren->name;
					$menuchildren->icon = $menuitemchildren->icon;
					$menuchildren->url = $menuitemchildren->url;
					$menuchildren->id_parent = $menu->id;
					$menuchildren->datasource_id = $menuitemchildren->datasource_id?$menuitemchildren->datasource_id:null;
					$menuchildren->order = $keychildren;
					$menuchildren->visible = 1;
					$menuchildren->system = @$menuitemchildren->system?$menuitemchildren->system:0;
					$menuchildren->menu_id = $menuId;
					$menuchildren->save();
				}
			}
		}

		if($menuoutlist) {
			foreach ($menuoutlist as $key => $menuitem) {
				if(!@$menuitem->datasource_id) {
					$menu = new MenuItem;
					$menu->name = $menuitem->name;
					$menu->icon = $menuitem->icon;
					$menu->url = $menuitem->url;
					$menu->id_parent = 0;
					$menu->datasource_id = null;
					$menu->order = $key;
					$menu->visible = 0;
					$menu->system = @$menuitem->system?$menuitem->system:0;
					$menu->menu_id = $menuId;
					$menu->save();

					if(count(@$menuitem->children)){
						foreach ($menuitem->children as $keychildren => $menuitemchildren) {
							if(!@$menuitemchildren->datasource_id) {
								$menuchildren = new MenuItem;
								$menuchildren->name = $menuitemchildren->name;
								$menuchildren->icon = $menuitemchildren->icon;
								$menuchildren->url = $menuitemchildren->url;
								$menuchildren->id_parent = $menu->id;
								$menuchildren->datasource_id = null;
								$menuchildren->order = $keychildren;
								$menuchildren->visible = 0;
								$menuchildren->system = @$menuitemchildren->system?$menuitemchildren->system:0;
								$menuchildren->menu_id = $menuId;
								$menuchildren->save();
							}
						}
					}
				}
			}
		}

  		$allMenus = MenuItem::orderBy('order')->get();
  		MenuItem::truncate();

  		$parentMenus = $allMenus->filter(function($menu) {
		    return $menu->id_parent == 0;
		})->values();

		foreach ($parentMenus as $menuitem) {
			$newMenu = new MenuItem;
			$newMenu->name = $menuitem->name;
			$newMenu->icon = $menuitem->icon;
			$newMenu->url = $menuitem->url;
			$newMenu->id_parent = 0;
			$newMenu->datasource_id = $menuitem->datasource_id;
			$newMenu->order = $menuitem->order;
			$newMenu->visible = $menuitem->visible;
			$newMenu->system = $menuitem->system;
			$newMenu->menu_id = $menuitem->menu_id;
			$newMenu->save();

			$subMenus = $allMenus->filter(function($submenu) use ($menuitem) {
			    return $submenu->id_parent == $menuitem->id;
			})->values();

			foreach ($subMenus as $submenuitem) {
				$newSubMenu = new MenuItem;
				$newSubMenu->name = $submenuitem->name;
				$newSubMenu->icon = $submenuitem->icon;
				$newSubMenu->url = $submenuitem->url;
				$newSubMenu->id_parent = $newMenu->id;
				$newSubMenu->datasource_id = $submenuitem->datasource_id;
				$newSubMenu->order = $submenuitem->order;
				$newSubMenu->visible = $submenuitem->visible;
				$newSubMenu->system = $submenuitem->system;
				$newSubMenu->menu_id = $submenuitem->menu_id;
				$newSubMenu->save();
				
			}

		}

		return Redirect::back()->with('success', Lang::get('cms::messages.success'));
	}

}

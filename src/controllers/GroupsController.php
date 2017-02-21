<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Insomnia\Cms\Models\Menu as Menu;
use Insomnia\Cms\Models\Datasource as Datasource;

use Config;
use Input;
use Lang;
use Redirect;
use Sentry;
use Validator;
use View;
use Session;

class GroupsController extends AdminController {

	public function getIndex()
	{
		AdminController::checkPermission('groups.view');

		$groups = Sentry::getGroupProvider()->createModel()->paginate();

		// Show the page
		return View::make('cms::groups/index', compact('groups'));
	}


	public function getCreate()
	{
		AdminController::checkPermission('groups.create');

        $groups = Sentry::getGroupProvider()->findAll();

		// Get all the available permissions
		$permissions = Config::get('cms::permissions');
		$this->encodeAllPermissions($permissions, true);

		// Selected permissions
		$selectedPermissions = Input::old('permissions', array());

		// Show the page
		return View::make('cms::groups/create', compact('permissions', 'selectedPermissions','groups'));
	}

	public function postCreate()
	{
		AdminController::checkPermission('groups.create');

		$rules = array(
			'name' => 'required',
            'copy' => 'required'
		);

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		try
		{
			// We need to reverse the UI specific logic for our
			// permissions here before we create the user.
			$permissions = Input::get('permissions', array());
			$this->decodePermissions($permissions);
			app('request')->request->set('permissions', $permissions);

			// Get the inputs, with some exceptions
			$inputs = Input::except('_token', 'copy');

			// Was the group created?
			if ($group = Sentry::getGroupProvider()->create($inputs))
			{
			    //copy permissions
                if(Input::get('copy')!=0){
                    $copyGroup = Sentry::getGroupProvider()->findById(Input::get('copy'));
                    $group->permissions = $copyGroup->permissions;
                    $group->save();
                }

				//create group menus
				$systemMenus = Menu::where('group_id', Input::get('copy'))->orderBy('order')->get();
		  		$parentMenus = $systemMenus->filter(function($menu) {
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
					$newMenu->group_id = $group->id;
					$newMenu->save();

					$subMenus = $systemMenus->filter(function($submenu) use ($menuitem) {
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
						$newSubMenu->group_id = $group->id;
						$newSubMenu->save();
						
					}

				}

				return Redirect::route('groups/edit', $group->id)->with('success', Lang::get('cms::groups/message.success.create'));
			}

			// Redirect to the new group page
			return Redirect::route('create/group')->with('error', Lang::get('cms::groups/message.error.create'));
		}
		catch (NameRequiredException $e)
		{
			$error = 'group_name_required';
		}
		catch (GroupExistsException $e)
		{
			$error = 'group_exists';
		}

		// Redirect to the group create page
		return Redirect::route('groups/create')->withInput()->with('error', Lang::get('cms::groups/message.'.$error));
	}

	public function getEdit($id = null)
	{

		AdminController::checkPermission('groups.view');

        if(Session::get('settings_super_user') && $id == 1) {
            return Redirect::route('groups')->with('error', 'Sem permissões');
        }

		try
		{
			// Get the group information
			$group = Sentry::getGroupProvider()->findById($id);

			// Get all the available permissions
			$permissions = Config::get('cms::permissions');
			$this->encodeAllPermissions($permissions, true);

			// Get this group permissions
			$groupPermissions = $group->getPermissions();
			$this->encodePermissions($groupPermissions);
			$groupPermissions = array_merge($groupPermissions, Input::old('permissions', array()));

			$datasources = Datasource::orderBy('name', 'DESC')->get();

            //get group menu
            $groupId = $group->id;

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
            ///

		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the groups management page
			return Redirect::route('groups')->with('error', Lang::get('cms::groups/message.group_not_found', compact('id')));
		}

		// Show the page
		return View::make('cms::groups/edit', compact('group', 'permissions', 'groupPermissions', 'datasources','menulist','allmenuoutlist'));
	}

	public function postEdit($id = null)
	{
		AdminController::checkPermission('groups.update');

		if(Session::get('settings_super_user') && $id == 1) {
			return Redirect::route('groups')->with('error', 'Sem permissões');
		}

		// We need to reverse the UI specific logic for our
		// permissions here before we update the group.
		$permissions = Input::get('permissions', array());
		$this->decodePermissions($permissions);
		app('request')->request->set('permissions', $permissions);

		try
		{
			// Get the group information
			$group = Sentry::getGroupProvider()->findById($id);
		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the groups management page
			return Redirect::route('groups')->with('error', Lang::get('cms::groups/message.group_not_found', compact('id')));
		}

		// Declare the rules for the form validation
		$rules = array(
			'name' => 'required',
		);

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		try
		{
			// Update the group data
			$group->name        = Input::get('name');
			$group->permissions = Input::get('permissions');

			if ($group->save())
			{
                //update menu
                $groupId = $group->id;

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


				return Redirect::route('groups/edit', $id)->with('success', Lang::get('cms::groups/message.success.update'));
			}
			else
			{
				// Redirect to the group page
				return Redirect::route('groups/edit', $id)->with('error', Lang::get('cms::groups/message.error.update'));
			}
		}
		catch (NameRequiredException $e)
		{
			$error = Lang::get('cms::group/message.group_name_required');
		}

		// Redirect to the group page
		return Redirect::route('groups/edit', $id)->withInput()->with('error', $error);
	}


	public function getDelete($id = null)
	{
		AdminController::checkPermission('groups.delete');

        //check if group is Admin or Editor (cms default groups)
		if($id == 1 || $id == 2) {
			return Redirect::route('groups')->with('error', 'O grupo não pode ser removido');
		}

		try
		{
			// Get group information
			$group = Sentry::getGroupProvider()->findById($id);

			// Delete the group
			$group->delete();

			// Delete group menu
			Menu::where('group_id', $id)->delete();

			// Redirect to the group management page
			return Redirect::route('groups')->with('success', Lang::get('cms::messages.success'));
		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the group management page
			return Redirect::route('groups')->with('error', Lang::get('cms::messages.not_found', compact('id')));
		}
	}

}

<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Config;
use Input;
use Lang;
use Redirect;
use Sentry;
use Validator;
use View;
use Menu;
use Datasource;
use Session;

class GroupsController extends AdminController {

	public function getIndex()
	{
		AdminController::checkPermission('groups.view');

		$groups = Sentry::getGroupProvider()->createModel()->paginate();

		// Show the page
		return View::make('ocms::groups/index', compact('groups'));
	}


	public function getCreate()
	{
		AdminController::checkPermission('groups.create');

		// Get all the available permissions
		$permissions = Config::get('permissions');
		$this->encodeAllPermissions($permissions, true);

		// Selected permissions
		$selectedPermissions = Input::old('permissions', array());

		// Show the page
		return View::make('ocms::groups/create', compact('permissions', 'selectedPermissions'));
	}

	public function postCreate()
	{
		AdminController::checkPermission('groups.create');

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
			// We need to reverse the UI specific logic for our
			// permissions here before we create the user.
			$permissions = Input::get('permissions', array());
			$this->decodePermissions($permissions);
			app('request')->request->set('permissions', $permissions);

			// Get the inputs, with some exceptions
			$inputs = Input::except('_token');

			// Was the group created?
			if ($group = Sentry::getGroupProvider()->create($inputs))
			{	

				//create group menus
				$systemMenus = Menu::where('group_id', 0)->orderBy('order')->get();
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

				return Redirect::route('update/group', $group->id)->with('success', Lang::get('admin/groups/message.success.create'));
			}

			// Redirect to the new group page
			return Redirect::route('create/group')->with('error', Lang::get('admin/groups/message.error.create'));
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
		return Redirect::route('create/group')->withInput()->with('error', Lang::get('admin/groups/message.'.$error));
	}

	public function getEdit($id = null)
	{

		AdminController::checkPermission('groups.view');

		try
		{
			// Get the group information
			$group = Sentry::getGroupProvider()->findById($id);

			// Get all the available permissions
			$permissions = Config::get('permissions');
			$this->encodeAllPermissions($permissions, true);

			// Get this group permissions
			$groupPermissions = $group->getPermissions();
			$this->encodePermissions($groupPermissions);
			$groupPermissions = array_merge($groupPermissions, Input::old('permissions', array()));

			$datasources = Datasource::orderBy('name', 'DESC')->get();

		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the groups management page
			return Redirect::route('groups')->with('error', Lang::get('admin/groups/message.group_not_found', compact('id')));
		}

		// Show the page
		return View::make('ocms::groups/edit', compact('group', 'permissions', 'groupPermissions', 'datasources'));
	}

	public function postEdit($id = null)
	{
		AdminController::checkPermission('groups.update');

		if(Session::get('settings_super_user') && $id == 1) {
			return Redirect::route('update/group', $id)->withInput()->with('error', 'Sem permissões');
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
			return Rediret::route('groups')->with('error', Lang::get('admin/groups/message.group_not_found', compact('id')));
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

			// Was the group updated?
			if ($group->save())
			{
				// Redirect to the group page
				return Redirect::route('update/group', $id)->with('success', Lang::get('admin/groups/message.success.update'));
			}
			else
			{
				// Redirect to the group page
				return Redirect::route('update/group', $id)->with('error', Lang::get('admin/groups/message.error.update'));
			}
		}
		catch (NameRequiredException $e)
		{
			$error = Lang::get('admin/group/message.group_name_required');
		}

		// Redirect to the group page
		return Redirect::route('update/group', $id)->withInput()->with('error', $error);
	}


	public function getDelete($id = null)
	{
		AdminController::checkPermission('groups.delete');

		if($id == 1) {
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
			return Redirect::route('groups')->with('success', Lang::get('_ocms/messages.success'));
		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the group management page
			return Redirect::route('groups')->with('error', Lang::get('_ocms/messages.not_found', compact('id')));
		}
	}

}

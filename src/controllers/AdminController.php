<?php namespace Insomnia\Cms\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;
use Insomnia\Cms\Models\Setting as Setting;
use Insomnia\Cms\Models\Menu as Menu;

use Session;
use Config;
use Redirect;

class AdminController extends Controller {

	public function __construct()
	{		
		// Apply the admin auth filter
		$this->beforeFilter('admin-auth');

		// Set default session language if none is set
		if(!Session::has('language'))
		{
		    Session::put('language', Config::get('app.locale'));
		}

		if (\Sentry::check()){
			$userGroup = \Sentry::getUser()->getGroups()[0]->id;
			$userGroupId = $userGroup?$userGroup:1;

			$group = \Sentry::getGroupProvider()->findById($userGroupId);
			$_groupPermissions = $group->getPermissions();
			View::share('_groupPermissions', $_groupPermissions);

			$settings = Setting::where('name', 'general')->first()->config();
			$menus = Menu::where('id_parent', 0)->where('visible', 1)->where('group_id', $userGroupId)->orderBy('order')->get();

            Session::put('settings_super_user', Setting::where('name', 'super_user')->first()->value&&$userGroupId!=1?true:false );


			View::share('menus', $menus);
			View::share('settings', $settings);
		}
	}

	public function checkPermission($requiredPermission) 
	{
		if (!\Sentry::getUser()->hasAccess($requiredPermission))
		{
			Redirect::route('cms')->send();
			die();
			return false;
		}

		return true;
	}


	/**
	 * Encodes the permissions so that they are form friendly.
	 *
	 * @param  array  $permissions
	 * @param  bool   $removeSuperUser
	 * @return void
	 */
	protected function encodeAllPermissions(array &$allPermissions, $removeSuperUser = false)
	{
		foreach ($allPermissions as $area => &$permissions)
		{
			foreach ($permissions as $index => &$permission)
			{
				if ($removeSuperUser == true and $permission['permission'] == 'superuser')
				{
					unset($permissions[$index]);
					continue;
				}

				$permission['can_inherit'] = ($permission['permission'] != 'superuser');
				$permission['permission']  = base64_encode($permission['permission']);
			}

			// If we removed a super user permission and there are
			// none left, let's remove the group
			if ($removeSuperUser == true and empty($permissions))
			{
				unset($allPermissions[$area]);
			}
		}
	}

	/**
	 * Encodes user permissions to match that of the encoded "all"
	 * permissions above.
	 *
	 * @param  array  $permissions
	 * @return void
	 */
	protected function encodePermissions(array &$permissions)
	{
		$encodedPermissions = array();

		foreach ($permissions as $permission => $access)
		{
			$encodedPermissions[base64_encode($permission)] = $access;
		}

		$permissions = $encodedPermissions;
	}

	/**
	 * Decodes user permissions to match that of the encoded "all"
	 * permissions above.
	 *
	 * @param  array  $permissions
	 * @return void
	 */
	protected function decodePermissions(array &$permissions)
	{
		$decodedPermissions = array();

		foreach ($permissions as $permission => $access)
		{
			$decodedPermissions[base64_decode($permission)] = $access;
		}

		$permissions = $decodedPermissions;
	}

}

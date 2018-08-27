<?php namespace Insomnia\Cms\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;
use Insomnia\Cms\Models\Setting as Setting;
use Insomnia\Cms\Models\Menu as Menu;
use Insomnia\Cms\Models\MenuItem as MenuItem;
use Insomnia\Cms\Classes\JWT as JWT;

use Session;
use Config;
use Redirect;

class AdminController extends Controller {

	public function __construct()
	{		
		// Apply the admin auth filter
		$this->beforeFilter('auth-'.Config::get('cms::config.auth_type'));
		
		//cms default lang
		Session::put('language', 'pt');
		$displayMenu = null;

		if (\Sentry::check()){
			$user = \Sentry::getUser();

			$displayMenus = Menu::lists('id');
			foreach($displayMenus as $displayMenuId){
				if(\CMS_Helper::checkPermission('display.menu.'.$displayMenuId)) {
					$displayMenu = $displayMenuId;
				}
			}

			$settings = Setting::where('name', 'general')->first()->config();
			$menus = MenuItem::where('id_parent', 0)->where('visible', 1)->where('menu_id', $displayMenu)->orderBy('order')->get();

			// settings_super_group is only active if is active in "settings" table and if the user group not have the "admin" permission 
			Session::put('settings_super_group', Setting::where('name', 'super_group')->first()->value&&!\CMS_Helper::checkPermission('admin')?true:false );

			View::share('menus', $menus);
			View::share('settings', $settings);
			View::share('CMS_USER', $user);
		}
	}

	public function checkPermission($requiredPermission) 
	{
		if(!\CMS_Helper::checkPermission($requiredPermission)){
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

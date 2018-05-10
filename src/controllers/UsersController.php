<?php namespace Insomnia\Cms\Controllers;

use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Config;
use Input;
use File;
use Lang;
use Redirect;
use Sentry;
use Validator;
use View;
use Image;
use Session;


class UsersController extends AdminController {

	protected $validationRules = array(
		'first_name'       => 'required|min:3',
		'last_name'        => 'required|min:3',
		'email'            => 'required|email|unique:users,email',
		'password'         => 'required|between:3,32',
		'password_confirm' => 'required|between:3,32|same:password',
		'photo' 		   => 'image|max:2100',
	);

	public function getIndex()
	{
		AdminController::checkPermission('users.view');

		// Grab all the users
		$users = Sentry::getUserProvider()->createModel();

		// Do we want to include the deleted users?
		if (Input::get('withTrashed'))
		{
			$users = $users->withTrashed();
		}
		else if (Input::get('onlyTrashed'))
		{
			$users = $users->onlyTrashed();
		}

		//if settings_super_user is active and current user not in admin group, hide admin users
		if(Session::get('settings_super_user') && Sentry::getUser()->getGroups()[0]->id != 1){
            $group = Sentry::findGroupById(1);
            $admins = Sentry::findAllUsersInGroup($group);
            $adminsId = [];
            foreach ($admins as $admin) {
                array_push($adminsId, $admin->id);
            }
            $users = $users->whereNotIn('id', $adminsId)->get();
		} else {
            $users = $users->get();
        }


		return View::make('cms::users/index', compact('users'));
	}

	public function getCreate()
	{
		AdminController::checkPermission('users.create');

		$groups = Sentry::getGroupProvider()->findAll();
		$selectedGroups = Input::old('groups', array());

		return View::make('cms::users/create', compact('groups', 'selectedGroups'));
	}


	public function postCreate()
	{
		AdminController::checkPermission('users.create');

		$this->validationRules['username'] = 'required|alpha_num|min:3|unique:users';

		$validator = Validator::make(Input::all(), $this->validationRules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		try
		{
			$inputs = Input::except('csrf_token', 'password_confirm', 'groups');

			if ($user = Sentry::getUserProvider()->create($inputs))
			{
				if(Input::file('photo')){
					$imageName = md5($user->username.date('YmdHis')).'.jpg';
					Image::make(Input::file('photo')->getRealPath())->fit(150, 150)->save(Config::get('cms::config.assets_path').'/users-photo/'.$imageName);
					$user->photo = $imageName;
				}

				if(Sentry::getUser()->hasAccess('users.group')){
					foreach (Input::get('groups', array()) as $groupId){

                        if(Session::get('settings_super_user') && $groupId == 1) {
                            return Redirect::route('users')->with('error', 'Sem permissões');
                        } else {
                            $group = Sentry::getGroupProvider()->findById($groupId);
                            $user->addGroup($group);
                        }

					}
				}
				//if no permission to modify users group, set the new user to group 2
				else {
					$group = Sentry::getGroupProvider()->findById(2);
					$user->addGroup($group);
				}

				$user->save();

				$success = Lang::get('cms::users/message.success.create');
				return Redirect::route('users/edit', $user->id)->with('success', $success);
			}

			$error = Lang::get('cms::users/message.error.create');

			// Redirect to the user creation page
			return Redirect::route('create/user')->with('error', $error);
		}
		catch (LoginRequiredException $e)
		{
			$error = Lang::get('cms::users/message.user_login_required');
		}
		catch (PasswordRequiredException $e)
		{
			$error = Lang::get('cms::users/message.user_password_required');
		}
		catch (UserExistsException $e)
		{
			$error = Lang::get('cms::users/message.user_exists');
		}

		// Redirect to the user creation page
		return Redirect::route('create/user')->withInput()->with('error', $error);
	}

	/**
	 * User update.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function getEdit($id = null)
	{
	    if(\Sentry::getUser()->id != $id) AdminController::checkPermission('users.view');

		try
		{
			// Get the user information
			$user = Sentry::getUserProvider()->findById($id);

            //if settings_super_user is active and the user to edit is in admin group, return error
            if(Session::get('settings_super_user') && @$user->getGroups()[0]->id == 1) {
                return Redirect::route('users')->with('error', 'Sem permissões');
            }

			// Get this user groups
			$userGroups = $user->groups()->lists('name', 'group_id');

            // Get this user permissions
            $userPermissions = array_merge(Input::old('permissions', array('superuser' => -1)), $user->getPermissions());
            $this->encodePermissions($userPermissions);

            // Get a list of all the available groups
            $groups = Sentry::getGroupProvider()->findAll();

            // Get all the available permissions
            $permissions = Config::get('permissions');
            $this->encodeAllPermissions($permissions);

		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('cms::users/message.user_not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}

		// Show the page
		return View::make('cms::users/edit', compact('user', 'groups', 'userGroups', 'permissions', 'userPermissions'));
	}

	/**
	 * User update form processing page.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function postEdit($id = null)
	{

        if(\Sentry::getUser()->id != $id) AdminController::checkPermission('users.update');

		// We need to reverse the UI specific logic for our
		// permissions here before we update the user.
//		$permissions = Input::get('permissions', array());
//		$this->decodePermissions($permissions);
//		app('request')->request->set('permissions', $permissions);

		try
		{
			$user = Sentry::getUserProvider()->findById($id);
            if(Session::get('settings_super_user') && @$user->getGroups()[0]->id == 1) {
                return Redirect::route('users')->with('error', 'Sem permissões');
            }

		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('cms::users/message.user_not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}

		//
		$this->validationRules['email'] = "required|email|unique:users,email,{$user->email},email";

		// Do we want to update the user password?
		if ( ! $password = Input::get('password'))
		{
			unset($this->validationRules['password']);
			unset($this->validationRules['password_confirm']);
			#$this->validationRules['password']         = 'required|between:3,32';
			#$this->validationRules['password_confirm'] = 'required|between:3,32|same:password';
		}

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $this->validationRules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		try
		{
			if(Input::file('photo')){
				$imageName = md5($user->username.date('YmdHis')).'.jpg';
				Image::make(Input::file('photo')->getRealPath())->fit(150, 150)->save(Config::get('cms::config.assets_path').'/users-photo/'.$imageName);
				if($user->photo){ File::delete(Config::get('cms::config.assets_path').'/users-photo/'.$user->photo); }
				$user->photo = $imageName;
			}

			// Update the user
			$user->first_name  = Input::get('first_name');
			$user->last_name   = Input::get('last_name');
			$user->email       = Input::get('email');
			$user->activated   = Input::get('activated', $user->activated);
//			$user->permissions = Input::get('permissions');

			// Do we want to update the user password?
			if ($password)
			{
				$user->password = $password;
			}

			if(Input::get('groups')[0]){

                if(Sentry::getUser()->hasAccess('users.group')){
                    // Get the current user groups
                    $userGroups = $user->groups()->lists('group_id', 'group_id');

                    // Get the selected groups
                    $selectedGroups = Input::get('groups', array());

                    // Groups comparison between the groups the user currently
                    // have and the groups the user wish to have.
                    $groupsToAdd    = array_diff($selectedGroups, $userGroups);
                    $groupsToRemove = array_diff($userGroups, $selectedGroups);

                    // Assign the user to groups
                    foreach ($groupsToAdd as $groupId)
                    {
                        if(Session::get('settings_super_user') && $groupId == 1) {
                            return Redirect::route('users/edit', $id)->with('error', 'Sem permissões');
                        } else {
                            $group = Sentry::getGroupProvider()->findById($groupId);
                            $user->addGroup($group);
                        }


                    }

                    // Remove the user from groups
                    foreach ($groupsToRemove as $groupId)
                    {
                        $group = Sentry::getGroupProvider()->findById($groupId);

                        $user->removeGroup($group);
                    }
                }
            }

			// Was the user updated?
			if ($user->save())
			{
				// Prepare the success message
				$success = Lang::get('cms::users/message.success.update');

				// Redirect to the user page
				return Redirect::route('users/edit', $id)->with('success', $success);
			}

			// Prepare the error message
			$error = Lang::get('cms::users/message.error.update');
		}
		catch (LoginRequiredException $e)
		{
			$error = Lang::get('cms::users/message.user_login_required');
		}

		// Redirect to the user page
		return Redirect::route('users/edit', $id)->withInput()->with('error', $error);
	}


	public function getDelete($id = null)
	{
		AdminController::checkPermission('users.delete');

		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->findById($id);
            if(@$user->getGroups()[0]->id == 1 && @Sentry::getUser()->getGroups()[0]->id != 1) {
                return Redirect::route('users')->with('error', 'Sem permissões');
            }

			// Check if we are not trying to delete ourselves
			if ($user->id === Sentry::getId())
			{
				// Prepare the error message
				$error = Lang::get('cms::users/message.error.delete');

				// Redirect to the user management page
				return Redirect::route('users')->with('error', $error);
			}

			// Delete the user
			$user->activated = 0;
			$user->save();
			$user->delete();

			// Prepare the success message
			$success = Lang::get('cms::users/message.success.delete');

			// Redirect to the user management page
			return Redirect::route('users')->with('success', $success);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('cms::users/message.user_not_found', compact('id' ));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
	}

	/**
	 * Restore a deleted user.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function getRestore($id = null)
	{
		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->createModel()->withTrashed()->find($id);

			// Restore the user
			$user->restore();

			// Prepare the success message
			$success = Lang::get('cms::users/message.success.restored');

			// Redirect to the user management page
			return Redirect::route('users')->with('success', $success);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('cms::users/message.user_not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
	}

}

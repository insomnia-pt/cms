<?php

Route::filter('auth-local', function()
{
    // Check if the user is logged in
    if (!Sentry::check())
    {
        // Store the current uri in the session
        Session::put('loginRedirect', Request::url());

        // Redirect to the login page
        return Redirect::route('signin');
    }

    // Check if the user has access to the admin page
    if (!Sentry::getUser()->hasAnyAccess(array('admin','cms')))
    {
        return App::abort(403);
    }
});


Route::filter('auth-keycloak', function()
{
    // Check if the user is logged in
    if (!Sentry::check())
    {
        $provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
			'authServerUrl'         => Config::get('cms::config.auth_types.keycloak.authServerUrl'),
			'realm'                 => Config::get('cms::config.auth_types.keycloak.realm'),
			'clientId'              => Config::get('cms::config.auth_types.keycloak.clientId'),
			'redirectUri'           => Config::get('cms::config.auth_types.keycloak.redirectUri')
		]);
		
		if (!Input::get('code')) {
		
			$authUrl = $provider->getAuthorizationUrl();
			Session::put('oauth2state', $provider->getState());
			return Redirect::to($authUrl);	
		} 
		elseif (!Input::get('state') || (Input::get('state') !== Session::get('oauth2state'))) {
			
			Session::forget('oauth2state');
			return 'Invalid state, make sure HTTP sessions are enabled.';
		
		} else {
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => Input::get('code')
                ]);
            } catch (Exception $e) {
                return 'Failed to get access token: '.$e->getMessage();
            }
        
            try {
                $user = $provider->getResourceOwner($token);
                
                if (!$CmsUser = User::where('username', $user->toArray()['preferred_username'])->first()) {
                    $CmsUser = new User;
                    $CmsUser->email = @$user->getEmail()?$user->getEmail():(Hash::make(str_random(8)).'@no.defined');
                    $CmsUser->username = $user->toArray()['preferred_username'];
                    $CmsUser->activated = 1;
                    $CmsUser->first_name = @$user->toArray()['given_name']?$user->toArray()['given_name']:'---';
                    $CmsUser->last_name = @$user->toArray()['family_name']?$user->toArray()['family_name']:'---';
                    $CmsUser->password = Hash::make(str_random(8));
                    $CmsUser->save();
                } else {
                    
                    if(@$user->getEmail()) $CmsUser->email = $user->getEmail();
                    $CmsUser->first_name = @$user->toArray()['given_name']?$user->toArray()['given_name']:'---';
                    $CmsUser->last_name = @$user->toArray()['family_name']?$user->toArray()['family_name']:'---';
                    $CmsUser->save();
                    
                }


                $CmsUser = Sentry::findUserById($CmsUser->id);
                Sentry::login($CmsUser, false);

               
        
                // printf('Hello %s!', $user->getName());
            } catch (Exception $e) {
                return 'Failed to get resource owner: '.$e->getMessage();
            }
        
            Session::put('token', $token);
        }

        
        return Redirect::to('/cms');

    } else {
        $provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
			'authServerUrl'         => Config::get('cms::config.auth_types.keycloak.authServerUrl'),
			'realm'                 => Config::get('cms::config.auth_types.keycloak.realm'),
			'clientId'              => Config::get('cms::config.auth_types.keycloak.clientId'),
			'redirectUri'           => Config::get('cms::config.auth_types.keycloak.redirectUri')
		]);
		
		$token = Session::get('token');

        if($token){
            if($token->hasExpired()){
                try {
                    $token = $provider->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);
                    Session::put('token', $token);
                } catch (Exception $e){
                    Session::forget('token');
                    Sentry::logout();
                }

                try {
                    // Check if the user has access to the admin page
                    if (!\CMS_Helper::checkPermission('cms'))
                    {
                        return App::abort(403);
                    }

                } catch (Exception $e){
                    Session::forget('token');
                    Sentry::logout();
                }
                
                return Redirect::to(Request::url());
            }
        } else {
            Sentry::logout();
            return Redirect::to('/cms');
        }

        // Check if the user has access to the admin page
        if (!\CMS_Helper::checkPermission('cms'))
        {
            return App::abort(403);
        }
        
    }

    // Check if the user has access to the admin page
    // if (!Sentry::getUser()->hasAnyAccess(array('admin','cms')))
    // {
    //     return App::abort(403);
    // }
});
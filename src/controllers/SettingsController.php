<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\Setting as Setting;
use Insomnia\Cms\Models\Datasource as Datasource;
use Input;
use Lang;
use Redirect;
use Validator;
use View;
use Str;
use Config;
use Schema;
use Helpers;
use DB;

use Session;

class SettingsController extends AdminController {


	public function getIndex()
	{
		AdminController::checkPermission('settings.view');

		$allSettings = Setting::all()->keyBy('name');
		// $table = CMS_ModelBuilder::fromTable($datasource->table)->get();
		// $datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();

		return View::make('cms::settings', compact('allSettings'));
	}


	public function postEdit($id = null)
	{
		AdminController::checkPermission('settings.update');

		foreach(Input::except('_token') as $setting => $value){
			echo $setting . ' - ' . $value;
			echo "<br />";
		}
dd();
		// $rules = array(
		// 	'name'   		=> 'required|min:3',
		// );

		// $validator = Validator::make(Input::all(), $rules);

		// if ($validator->fails())
		// {
		// 	return Redirect::back()->withInput()->withErrors($validator);
		// }

  		// $datasource->name = Input::get('name');

		// if($datasource->save())
		// {
			return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		// }

		return Redirect::back()->with('error', Lang::get('cms::settings/message.update.error'));
	}


	public function getKeycloakRoles()
	{
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
                } catch (Exception $e){
                    Session::forget('token');
                    Sentry::logout();
                }
			}
			
				$url = Config::get('cms::config.auth_types.keycloak.authServerUrl').'/admin/realms/'.Config::get('cms::config.auth_types.keycloak.realm').'/clients/'.Config::get('cms::config.auth_types.keycloak.clientUuid').'/roles';

				$permissions = Config::get('cms::permissions');
				foreach($permissions as $key => $permission) {

                    foreach ($permission as $role) {

						echo $role['permission'];
						echo $key .' - '. $role['label'];
						echo '<br /><br />';

						$client = new \GuzzleHttp\Client();
                        $res = $client->post($url, [
							'exceptions' => false,
							'headers' => ['Authorization' => 'Bearer '.$token->getToken(), 'Content-Type' => 'application/json'],
							'json' => [
								'name' => $role['permission'],
								'description' =>  $key .' - '. $role['label']
							],
						]);
                    }
				}

				$datasources = Datasource::get();
				foreach ($datasources as $datasource){
					if($datasource->permissions()) {
                        foreach ($datasource->permissions() as $permission) {
							
							echo $datasource->table.'.'.$permission;
                            echo $datasource->name .' - '. Lang::get('cms::permissions.'.$permission);
                            echo '<br /><br />';

                            $client = new \GuzzleHttp\Client();
                            $res = $client->post($url, [
                                'exceptions' => false,
                                'headers' => ['Authorization' => 'Bearer '.$token->getToken(), 'Content-Type' => 'application/json'],
                                'json' => [
                                    'name' => $datasource->table.'.'.$permission,
                                    'description' => $datasource->name .' - '. Lang::get('cms::permissions.'.$permission)
                                ],
                            ]);
                        }
						
					}
				}

	

        }
	}

}

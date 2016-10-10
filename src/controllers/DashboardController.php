<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\User as User;
use View;
use Page;
use Session;
use Redirect;
use DateTime;

use Analytics;

class DashboardController extends AdminController {

	/**
	 * Show the administration dashboard page.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		$tempFix = 1;
		$ga_access_token = null;
		if(!$tempFix){
			$gaClient = Analytics::getClient();
			$gaClient->getAuth()->refreshTokenWithAssertion();
			$ga_access_token = json_decode($gaClient->getAccessToken());
		}

		return View::make('cms::dashboard', compact('users', 'pages','ga_access_token'));
	}

	public function modoProgramador()
	{
		Session::put('modoProgramador', 1);
		return Redirect::route('admin')->with('success', 'Modo Programador Activado');
	}

	public function setLang($lang)
	{
		Session::put('language', $lang);
		return Redirect::route('admin')->with('success', 'Idioma de edição alterado');
	}
}

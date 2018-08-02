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
		return View::make('cms::dashboard', compact('users', 'pages'));
	}

}

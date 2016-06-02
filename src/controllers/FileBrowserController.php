<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use View;


class FileBrowserController extends AdminController {

	public function getIndex()
	{	
		AdminController::checkPermission('filebrowser.view');

		return View::make('ocms::filebrowser');
	}

}

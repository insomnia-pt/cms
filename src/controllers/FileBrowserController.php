<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Barryvdh\Elfinder\Connector;
use View;
use Config;


class FileBrowserController extends AdminController {

	public function getIndex()
	{	
		AdminController::checkPermission('filebrowser.view');

		return View::make('cms::filebrowser');
	}

	public function showConnector()
    {
        $dir = Config::get('cms::config.elfinder_dir');
        $roots = Config::get('cms::config.elfinder_roots');


        if (!$roots) {
            $roots = array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path() . DIRECTORY_SEPARATOR . $dir, // path to files (REQUIRED)
                    'URL' => Config::get('app.url').'/'.$dir, // URL to files (REQUIRED)
                    'accessControl' => Config::get('cms::config.elfinder_access') // filter callback (OPTIONAL)
                )
            );
        }

        $opts = Config::get('cms::config.elfinder_options');
        $opts = array_merge(array(
                'roots' => $roots
            ), $opts);

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

}

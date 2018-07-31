<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\Setting as Setting;
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

		$rules = array(
			'name'   		=> 'required|min:3',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

  		$datasource->name = Input::get('name');

		// if($datasource->save())
		// {
		// 	return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		// }

		return Redirect::back()->with('error', Lang::get('cms::settings/message.update.error'));
	}


}

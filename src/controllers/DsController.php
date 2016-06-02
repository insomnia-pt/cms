<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Input;
use Lang;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;
use Helpers;
use Session;
use Config;

use DatasourceFieldtype;
use Datasource;
use ModelBuilder;

class DsController extends AdminController {

	public function parameters() {
		$parameters = array('pds'=>Input::get('pds'), 'item'=> Input::get('item'));

		return $parameters;
	}

	public function getIndex($id)
	{
		if (is_null($datasource = Datasource::find($id))){
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'view');

		$orderBy = @$datasource->options()->orderby?$datasource->options()->orderby:'order';

		$parentDatasource = null;
		$parentDatasourceItem = null;
		if(Input::get('pds')&&Input::get('item')){
			if (is_null($parentDatasource = Datasource::find(Input::get('pds')))) {
				return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
			}
			$parentDatasourceItem = ModelBuilder::fromTable($parentDatasource->table)->find(Input::get('item'));
			$dsItems = ModelBuilder::fromTable($datasource->table)->where($parentDatasource->table.'_id', Input::get('item'))->orderBy($orderBy)->get();

		} else {
			$dsItems = ModelBuilder::fromTable($datasource->table)->orderBy($orderBy)->get();
		}

		$parameters = $this::parameters();

		return View::make('ocms::ds/index', compact('datasource','parentDatasource','dsItems','parameters','parentDatasourceItem'));
	}

	public function getCreate($id)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'create');

		$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();
		$parameters = $this::parameters();

		return View::make('ocms::ds/create', compact('datasource','parameters','datasourceFieldtypes'));
	}

	public function postCreate($id)
	{
		if (is_null($datasource = Datasource::find($id))) {
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'create');

		$inputs = Input::except('_token','pds','item');
		if(isset($inputs['id_parent'])) if($inputs['id_parent']==''){ $inputs['id_parent'] = null; }

		if(Input::get('pds')){
			if (is_null($parentDatasource = Datasource::find(Input::get('pds')))) {
				return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
			}

			$inputs[$parentDatasource->table.'_id'] = Input::get('item');
		}
		
		$ds = ModelBuilder::fromTable($datasource->table);
		
		
		if($ds->create($inputs)) {
			return Redirect::back()->with('success', Lang::get('_ocms/ds/message.success.create'));
		}

		return Redirect::to('ocms/ds/'.$datasource->id.'/create')->with('error', Lang::get('_ocms/ds/message.error.create'));
	}

	public function getEdit($id, $itemId)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'view');

		$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();
		$dsItem = ModelBuilder::fromTable($datasource->table)->find($itemId);
		$parameters = $this::parameters();

		return View::make('ocms::ds/edit', compact('datasource','dsItem','parameters','datasourceFieldtypes'));
	}

	public function postEdit($id, $itemId)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'update');

		$inputs = Input::except('_token','pds','item');
		if(isset($inputs['id_parent'])) if($inputs['id_parent']==''){ $inputs['id_parent'] = null; }

		$dsItem = ModelBuilder::fromTable($datasource->table)->find($itemId);

		if($dsItem->update($inputs))
		{
			return Redirect::back()->with('success', Lang::get('_ocms/ds/message.success.update'));
		}

		return Redirect::to("ocms/ds/$id")->with('error', Lang::get('_ocms/ds/message.error.update'));
	}

	public function getDelete($id, $itemId)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'delete');

		$dsItem = ModelBuilder::fromTable($datasource->table)->find($itemId);
		if(@$datasource->options()->subitems){
			ModelBuilder::fromTable($datasource->table)->where('id_parent', $dsItem->id)->update(array('id_parent' => null));
		}
		$dsItem->delete();

		return Redirect::to('ocms/ds/'.$id)->with('success', Lang::get('_ocms/ds/message.success.delete'));
	}

	public function getSubIndex($dsId, $itemId, $subDsId)
	{
		if (is_null($datasource = Datasource::find($dsId)))
		{
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		if (is_null($subDatasource = Datasource::find($subDsId)))
		{
			return Redirect::to('ocms')->with('error', Lang::get('_ocms/ds/message.does_not_exist'));
		}

		$dsItems = ModelBuilder::fromTable($subDatasource->table)->where($datasource->table.'_id', $itemId)->get();

		return View::make('ocms::ds/index', compact('datasource','subDatasource','dsItems'));
	}

}


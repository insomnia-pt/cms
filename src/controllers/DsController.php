<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\DatasourceFieldtype as DatasourceFieldtype;
use Insomnia\Cms\Models\Datasource as Datasource;
use Insomnia\Cms\Models\DatasourceRelation as DatasourceRelation;
use Insomnia\Cms\Models\ModelBuilder as CMS_ModelBuilder;
use Insomnia\Cms\Models\Setting as Setting;

use Input;
use Lang;
use Redirect;
use Str;
use Validator;
use View;
use Helpers;
use Session;
use Config;
use URL;


class DsController extends AdminController {

	public function parameters() {
		$parameters = array('pds'=>Input::get('pds'), 'item'=> Input::get('item'));

		return $parameters;
	}

	public function getIndex($id)
	{
		if (is_null($datasource = Datasource::find($id))){
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'view');

		$orderBy = @$datasource->options()->orderby?$datasource->options()->orderby:'order';

		$parentDatasource = null;
		$parentDatasourceItem = null;
		if(Input::get('pds')&&Input::get('item')){
			if (is_null($parentDatasource = Datasource::find(Input::get('pds')))) {
				return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
			}
			$parentDatasourceItem = CMS_ModelBuilder::fromTable($parentDatasource->table)->find(Input::get('item'));
			$dsItems = CMS_ModelBuilder::fromTable($datasource->table)->where($parentDatasource->table.'_id', Input::get('item'))->orderBy($orderBy)->get();

		} else {
			$dsItems = CMS_ModelBuilder::fromTable($datasource->table)->orderBy($orderBy)->get();
		}

		$parameters = $this::parameters();

        $datasourceFieldtypes = DatasourceFieldtype::get();

		return View::make('cms::ds/index', compact('datasource','parentDatasource','dsItems','parameters','parentDatasourceItem','datasourceFieldtypes'));
	}

	public function getCreate($id)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'create');

		$languages = Setting::where('name', 'languages')->first()->config();
		$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();
		$parameters = $this::parameters();

		return View::make('cms::ds/create', compact('datasource','parameters','datasourceFieldtypes','languages'));
	}

    /**
     * @param $id
     * @return mixed
     */
    public function postOrder($id)
	{
		if (is_null($datasource = Datasource::find($id))) {
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		//se houver parent datasource (pds) e parent id (item), retorna no link
		$returnUrlParams = null;
		if(Input::get('pds')){ $returnUrlParams = '?pds='.Input::get('pds').'&item='.Input::get('item'); }
		//////

		if(Input::get('ds-orderlist')){

			$orderIds = explode(',', Input::get('ds-orderlist'));

			foreach ($orderIds as $index => $id) {
				CMS_ModelBuilder::fromTable($datasource->table)->where('id', $id)->update(array('order' => $index));
			}

			return Redirect::to('cms/ds/'.$datasource->id.$returnUrlParams)->with('success', Lang::get('cms::ds/message.success.order'));
		}
		else if(Input::get('ds-orderlist-listview')) {
			$orderIds = json_decode(Input::get('ds-orderlist-listview'));

			$this->recursiveUpdate($datasource, $orderIds);

			return Redirect::to('cms/ds/'.$datasource->id.$returnUrlParams)->with('success', Lang::get('cms::ds/message.success.order'));
		}

		return Redirect::to('cms/ds/'.$datasource->id.$returnUrlParams)->with('error', Lang::get('cms::ds/message.error.order'));
	}

    public function postAjaxComponent($id)
    {
        if (is_null($datasource = Datasource::find($id)))
        {
            return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
        }

        AdminController::checkPermission($datasource->table.'.'.'update');

        $itemId = Input::get('id');

        //only allow this inputs
        $inputsAllowed = [];
        foreach ($datasource->config() as $key => $config) {
            array_push($inputsAllowed, $config->name);
        }

        // dd($inputsAllowed);
        ////

        $inputs = Input::only($inputsAllowed);
        $inputs = array_filter($inputs, 'strlen');
        $dsItem = CMS_ModelBuilder::fromTable($datasource->table)->find($itemId);

        if($dsItem->update($inputs)) {

            Helpers::cmslog('Edição', $dsItem, $datasource->id, $itemId);

            return 'Ok';
        }

        return 'Error';
    }

	public function postCreate($id)
	{
		if (is_null($datasource = Datasource::find($id))) {
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'create');

		//only allow this inputs
		$inputsAllowed = ['id_parent'];
		foreach ($datasource->config() as $key => $config) {
			array_push($inputsAllowed, $config->name);
		}

		foreach ($datasource->relations as $key => $relation) {
			array_push($inputsAllowed, Datasource::find($relation->relation_datasource_id)->table.'_id');
		}

		if(Input::get('pds')){
			if (is_null($parentDatasource = Datasource::find(Input::get('pds')))) {
				return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
			}

			array_push($inputsAllowed, $parentDatasource->table.'_id');
			$inputs = Input::only($inputsAllowed);
			$inputs[$parentDatasource->table.'_id'] = Input::get('item');
		} else {
			$inputs = Input::only($inputsAllowed);
		}
		////

		if(isset($inputs['id_parent'])) if($inputs['id_parent']==''){ $inputs['id_parent'] = null; }

		//caso o input seja array (por exemplo nos campos multilang) faz o encoding para json
		foreach($inputs as $key => $input) {
			if(is_array($input)) $inputs[$key] = json_encode($input);
		}


		$ds = CMS_ModelBuilder::fromTable($datasource->table);
		$ds->fill(array_filter($inputs, 'strlen'));

		//se houver parent datasource (pds) e parent id (item), retorna no link
		$returnUrlParams = null;
		if(Input::get('pds')){ $returnUrlParams = '?pds='.Input::get('pds').'&item='.Input::get('item'); }
		//////

		if($ds->save()) {
            Helpers::cmslog('Inserção', $inputs, $datasource->id, $ds->id);

			return Redirect::to('cms/ds/'.$datasource->id.'/edit/'.$ds->id.$returnUrlParams)->with('success', Lang::get('cms::ds/message.success.create'));
		}

		return Redirect::to('cms/ds/'.$datasource->id.'/create')->with('error', Lang::get('cms::ds/message.error.create'));
	}

	public function getEdit($id, $itemId)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'view');

		$languages = Setting::where('name', 'languages')->first()->config();
		$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();
		$dsItem = CMS_ModelBuilder::fromTable($datasource->table)->find($itemId);
		$parameters = $this::parameters();

		return View::make('cms::ds/edit', compact('datasource','dsItem','parameters','datasourceFieldtypes','languages'));
	}

	public function postEdit($id, $itemId)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'update');

		//only allow this inputs
		$inputsAllowed = [];
		foreach ($datasource->config() as $key => $config) {
			array_push($inputsAllowed, $config->name);
		}

		if($datasource->options()->subitems){ array_push($inputsAllowed, 'id_parent'); }

		$relations = $datasource->relations;
		foreach ($relations as $key => $relation) {
			if($relation->relation_type == 'hasOne'){
				array_push($inputsAllowed, Datasource::find($relation->relation_datasource_id)->table.'_id');
			}
		}

		// dd($inputsAllowed);
		////

		$inputs = Input::only($inputsAllowed);
		$dsItem = CMS_ModelBuilder::fromTable($datasource->table)->find($itemId);

		//caso o input seja array (por exemplo nos campos multilang) faz o encoding para json
		foreach($inputs as $key => $input) {
			if(is_array($input)) $inputs[$key] = json_encode($input);
		}

		if($dsItem->update($inputs)) {

            Helpers::cmslog('Edição', $dsItem, $datasource->id, $itemId);

			return Redirect::to(URL::previous())->with('success', Lang::get('cms::ds/message.success.update'));
		}

		return Redirect::to("cms/ds/$id")->with('error', Lang::get('cms::ds/message.error.update'));
	}


	public function getDelete($id, $itemId)
	{
		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		AdminController::checkPermission($datasource->table.'.'.'delete');

		$dsItem = CMS_ModelBuilder::fromTable($datasource->table)->find($itemId);
		if(@$datasource->options()->subitems){
			CMS_ModelBuilder::fromTable($datasource->table)->where('id_parent', $dsItem->id)->update(array('id_parent' => null));
		}
		$dsItem->delete();

        Helpers::cmslog('Remoção', $dsItem, $datasource->id, $itemId);

		return Redirect::to(URL::previous())->with('success', Lang::get('cms::ds/message.success.delete'));
	}


	public function getSubIndex($dsId, $itemId, $subDsId)
	{
		if (is_null($datasource = Datasource::find($dsId)))
		{
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		if (is_null($subDatasource = Datasource::find($subDsId)))
		{
			return Redirect::to('cms')->with('error', Lang::get('cms::ds/message.does_not_exist'));
		}

		$dsItems = CMS_ModelBuilder::fromTable($subDatasource->table)->where($datasource->table.'_id', $itemId)->get();

		return View::make('cms::ds/index', compact('datasource','subDatasource','dsItems'));
	}

	private function recursiveUpdate($datasource, $itemBranch, $parentBranch = null)
	{
		foreach ($itemBranch as $index => $item) {
			CMS_ModelBuilder::fromTable($datasource->table)->where('id', $item->id)->update(array('id_parent' => $parentBranch?$parentBranch:null, 'order' => $index));
			if(@$item->children){ self::recursiveUpdate($datasource, $item->children, $item->id); }
		}
	}

}

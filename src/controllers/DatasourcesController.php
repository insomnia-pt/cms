<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Controllers\AdminController;
use Insomnia\Cms\Models\Datasource as Datasource;
use Insomnia\Cms\Models\DatasourceRelation as DatasourceRelation;
use Insomnia\Cms\Models\DatasourceFieldtype as DatasourceFieldtype;
use Insomnia\Cms\Models\MenuItem as MenuItem;
use Insomnia\Cms\Models\ModelBuilder as CMS_ModelBuilder;
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

class DatasourcesController extends AdminController {


	public function getIndex()
	{
		AdminController::checkPermission('datasources.view');

		$datasources = Datasource::orderBy('created_at', 'DESC')->get();
		return View::make('cms::datasources/index', compact('datasources'));
	}

	public function getCreate()
	{
		AdminController::checkPermission('datasources.create');

		$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();
		return View::make('cms::datasources/create', compact('datasourceFieldtypes'));
	}

	public function postCreate()
	{
		AdminController::checkPermission('datasources.create');

		$rules = array(
			'name'   		=> 'required|min:3|unique:datasources,name',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$tableConfig = json_decode(Input::get('table_config'), true);

        if($this->DsCreate(Input::get('name'), Input::get('subitems')?1:0, $tableConfig)){
            return Redirect::to("cms/datasources")->with('success', Lang::get('cms::datasources/message.create.success'));
        }

		return Redirect::to('cms/datasources/create')->with('error', Lang::get('cms::datasources/message.create.error'));
	}


	public function getEdit($id = null)
	{
		AdminController::checkPermission('datasources.view');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		$datasources = Datasource::get();
		$table = CMS_ModelBuilder::fromTable($datasource->table)->get();
		$datasourceFieldtypes = DatasourceFieldtype::orderBy('id')->get();

		return View::make('cms::datasources/edit', compact('datasource','datasources','table','datasourceFieldtypes'));
	}


	public function postEdit($id = null)
	{
		AdminController::checkPermission('datasources.update');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		$rules = array(
			'name'   		=> 'required|min:3',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

  		$datasource->name = Input::get('name');

		if($datasource->save())
		{
			return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		}

		return Redirect::back()->with('error', Lang::get('cms::datasources/message.update.error'));
	}

	public function postEditFieldCreate($id = null)
	{
		AdminController::checkPermission('datasources.update');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		$rules = array(
			'description'  		=> 'required',
			'datatype'   			=> 'required',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$datasourceFieldtypes = DatasourceFieldtype::get();
		$newField = array(
			'description'  		=> Input::get('description'),
			'datatype'   		=> Input::get('datatype'),
			'show_in_table'   	=> Input::get('show_in_table'),
			'multilang'   		=> Input::get('multilang'),
			'size'   			=> Input::get('size'),
			'name' 				=> Str::slug(Input::get('description'), '_')
		);

		if(count(Input::get('parameters'))){
			$newField['parameters'] = Input::get('parameters');
		}

	    Schema::table($datasource->table, function($table) use($newField, $datasourceFieldtypes) {
	        $table->{$datasourceFieldtypes->find($newField['datatype'])->type}($newField['name']);
	    });

		$datasourceConfigs = $datasource->config();
		array_push($datasourceConfigs, $newField);
		$datasource->config = stripslashes(json_encode($datasourceConfigs, JSON_UNESCAPED_UNICODE));

		if($datasource->save())
		{
			return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		}

		return Redirect::back()->with('error', Lang::get('cms::datasources/message.update.error'));
	}

	public function postEditFieldEdit($id, $fieldName)
	{
		AdminController::checkPermission('datasources.update');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		$rules = array(
			'description'  		=> 'required',
			'name'  			=> 'required',
			'datatype'   		=> 'required',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$datasourceFieldtypes = DatasourceFieldtype::get();
		$editField = array(
			'description'  		=> Input::get('description'),
			'datatype'   		=> Input::get('datatype'),
			'show_in_table'   	=> Input::get('show_in_table'),
			'multilang'   		=> Input::get('multilang'),
			'size'		   		=> Input::get('size'),
			'name' 				=> Str::slug(Input::get('name'), '_')
		);

		if(count(Input::get('parameters'))){
			$editField['parameters'] = Input::get('parameters');
		}

		$queryFieldType = Helpers::translateFieldTypes($editField['datatype']);


	    DB::statement('ALTER TABLE `'.$datasource->table.'` CHANGE `'.$fieldName.'` `'.$editField['name'].'` '.$queryFieldType);

	    $datasourceConfigs = $datasource->config();
		$key = $this->searchForFieldName($fieldName, $datasourceConfigs);
		$datasourceConfigs[$key] = $editField;
		$datasource->config = stripslashes(json_encode($datasourceConfigs, JSON_UNESCAPED_UNICODE));

		if($datasource->save())
		{
			return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		}

		return Redirect::back()->with('error', Lang::get('cms::datasources/message.update.error'));
	}

	public function postEditRelationCreate($id = null)
	{
		AdminController::checkPermission('datasources.update');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		$rules = array(
			'description'  		=> 'required',
			'type'   			=> 'required',
			'datasource'   		=> 'required',
			'config_identify'   		=> 'required',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$relationDatasource = Datasource::find(Input::get('datasource'));

		switch (Input::get('type')) {

			case 'hasOne':
				Schema::table($datasource->table, function($table) use($relationDatasource, $datasource) {
				$table->integer($relationDatasource->table.'_id')->nullable();
			});

			break;

			case 'hasMany':
				Schema::table($relationDatasource->table, function($table) use($relationDatasource, $datasource) {
				$table->integer($datasource->table.'_id')->nullable();
			});
			break;

			case 'belongsToMany':
				Schema::create($datasource->table.'_'.$relationDatasource->table, function($table) use($relationDatasource, $datasource) {
					$table->engine = 'InnoDB';
					$table->integer($datasource->table.'_id')->unsigned();
					$table->integer($relationDatasource->table.'_id')->unsigned();
				});
				
			break;

		}

		$datasourceRelation = new DatasourceRelation(array(
			'relation_datasource_id' => Input::get('datasource'),
			'relation_type' => Input::get('type'),
			'relation_description' => Input::get('description'),
			'config' => stripslashes(json_encode(array("fields" => array(Input::get('config_identify')), "area" => Input::get('config_area'))))
		));

		$datasource->relations()->save($datasourceRelation);


		if($datasource->save()) {
			return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		}

		return Redirect::back()->with('error', Lang::get('cms::datasources/message.update.error'));
	}



	public function getDelete($id)
	{
		AdminController::checkPermission('datasources.delete');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		$this->DsDelete($datasource);

		return Redirect::to('cms/datasources')->with('success', Lang::get('cms::datasources/message.delete.success'));
	}

	public function getDeleteRelation($id, $relationId)
	{
		AdminController::checkPermission('datasources.update');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		if (is_null($relation = DatasourceRelation::find($relationId)))
		{
			return Redirect::to('cms/datasources/'.$datasource->id.'/edit')->with('error', Lang::get('cms::datasources/relation/message.does_not_exist'));
		}

		// $datasources = Datasource::get();
		// $relationDatasource = $datasources->find($relationDatasource);

		switch ($relation->relation_type) {
			case 'hasOne':
				Schema::table($datasource->table, function($table) use($relation) {
					$table->dropColumn($relation->relation_datasource->table.'_id');
				});
				break;

			case 'hasMany':
				Schema::table($relation->relation_datasource->table, function($table) use($datasource) {
					$table->dropColumn($datasource->table.'_id');
				});
				break;

			case 'belongsToMany':
				Schema::drop($datasource->table.'_'.$relation->relation_datasource->table);
				break;
		}

		$relation->delete();

		return Redirect::to('cms/datasources/'.$datasource->id.'/edit')->with('success', Lang::get('cms::datasources/message.delete.success'));
	}

	public function getDeleteField($id, $fieldName)
	{
		AdminController::checkPermission('datasources.update');

		if (is_null($datasource = Datasource::find($id)))
		{
			return Redirect::to('cms/datasources')->with('error', Lang::get('cms::datasources/message.does_not_exist'));
		}

		Schema::table($datasource->table, function($table) use($fieldName) {
		    $table->dropColumn($fieldName);
		});

		$datasourceConfigs = $datasource->config();
		$key = $this->searchForFieldName($fieldName, $datasourceConfigs);
		unset($datasourceConfigs[$key]);
		$datasource->config = stripslashes(json_encode($datasourceConfigs, JSON_UNESCAPED_UNICODE));

		if($datasource->save())
		{
			return Redirect::back()->with('success', Lang::get('cms::datasources/message.update.success'));
		}

		return Redirect::to('cms/datasources/'.$datasource->id.'/edit')->with('success', Lang::get('cms::datasources/message.delete.success'));
	}

	private function searchForFieldName($name, $array) {
	   foreach ($array as $key => $val) {
	       if ($val->name == $name) {
	           return $key;
	       }
	   }
	   return null;
	}


	static function DsCreate($dsName, $dsSubItems, $dsTableConfig){

        $date = new \DateTime();
		$newTableName = Str::limit(Config::get('cms::config.datasource_table_prefix').Str::slug($dsName), 20, '');

        $newtableschema = array(
            'table_name' => $newTableName,
            'table_config' => $dsTableConfig,
            'table_options' => array(
                'subitems' => $dsSubItems,
                'permissions' => array('view','create','update','delete')
            )
        );

        $datasourceFieldtypes = DatasourceFieldtype::get();

        //converte descrição em nome da tabela e adiciona parametros adicionais (tipo de datasource field) ao array
        foreach($newtableschema['table_config'] as $index => $col){
            $newtableschema['table_config'][$index]['name'] = Str::slug($col['description'], '_');
            $parameters = @$datasourceFieldtypes->find($col['datatype'])->config()->parameters;
            if($parameters){
                foreach ($parameters as $parameter) {
                    $newtableschema['table_config'][$index][$parameter] = '';
                }
            }
        }

        $datasourceFieldtypes = DatasourceFieldtype::get();

        DB::beginTransaction();

        Schema::create($newtableschema['table_name'], function($table) use($newtableschema, $datasourceFieldtypes) {
            $table->increments('id')->unique();
            $table->integer('order')->nullable();

            foreach($newtableschema['table_config'] as $col) {
                $table->{$datasourceFieldtypes->find($col['datatype'])->type}(Str::slug($col['description'], '_'));
            }

            if($newtableschema['table_options']['subitems']){
                $table->integer('id_parent')->nullable();
            }

            $table->timestamps();
        });

        $datasource = new Datasource;
        $datasource->name        	= $dsName;
        $datasource->table        	= $newtableschema['table_name'];
        $datasource->config        	= stripslashes(json_encode($newtableschema['table_config'], JSON_UNESCAPED_UNICODE));
        $datasource->options        = stripslashes(json_encode($newtableschema['table_options']));

        if($datasource->save()) {
            DB::commit();
            return $datasource;
        }

        return false;
    }

    static function DsDelete($datasource){

        Schema::drop($datasource->table);
        MenuItem::where('datasource_id', $datasource->id)->delete();
        $datasource->relations()->delete();
        $datasource->delete();

    }

}
